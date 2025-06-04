<?php
// assets/cPhp/update_tracking.php
// Query the 17track API for shipment updates and optionally
// update WooCommerce order statuses.

require_once __DIR__ . '/master-api.php';

$apiKey = getenv('TRACK17_APIKEY');
if (!$apiKey) {
    http_response_code(500);
    echo json_encode(['error' => 'TRACK17_APIKEY not set']);
    exit;
}

/**
 * Helper to call WooCommerce API
 */
function wooRequest($endpoint, $method = 'GET', $data = null) {
    global $store_url, $consumer_key, $consumer_secret;
    $url = rtrim($store_url, '/') . $endpoint;
    $ch  = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$consumer_key:$consumer_secret");
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    if ($method !== 'GET') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }

    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [$code, json_decode($resp, true)];
}

/**
 * Call the 17track realtime API.
 */
function call17Track($carrier, $number, $apiKey) {
    $payload = ['number' => $number];
    if (!empty($carrier)) {
        $payload['carrier'] = $carrier;
    }

    $ch = curl_init('https://api.17track.net/track/v2/realtime');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        '17token: ' . $apiKey,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    $resp = curl_exec($ch);
    if (curl_errno($ch)) {
        curl_close($ch);
        return null;
    }

    curl_close($ch);
    return json_decode($resp, true);
}

/**
 * Extract a simplified event from the 17track response.
 */
function parseEvent($res) {
    if (!is_array($res)) return [null, null, null, null];

    $data = $res['data'][0] ?? [];
    $status = $data['status'] ?? '';
    $event  = $data['last_event'] ?? '';
    $time   = $data['last_event_time'] ?? '';

    $type = null;
    $low  = strtolower($event);
    if (strpos($low, 'customs') !== false) {
        $type = 'customs_hold';
    } elseif (strpos($low, 'delivered') !== false || strtolower($status) === 'delivered') {
        $type = 'delivered';
    } elseif (strpos($low, 'exception') !== false) {
        $type = 'exception';
    }

    return [$type, $status, $time, $event];
}

// ---------------------------------------------------------------------
// 1) Get all in-transit orders with tracking numbers
$orders = [];
$page = 1;
while (true) {
    list($code, $batch) = wooRequest("/wp-json/wc/v3/orders?status=in-transit&per_page=100&page={$page}");
    if ($code !== 200 || empty($batch)) {
        break;
    }
    $orders = array_merge($orders, $batch);
    $page++;
}

$events = [];
$delayed = [];
$threshold = 9; // days
$now = new DateTime('now');
foreach ($orders as $o) {
    $carrier = $number = '';
    foreach ($o['meta_data'] as $m) {
        if ($m['key'] === '_wot_tracking_carrier') $carrier = $m['value'];
        if ($m['key'] === '_wot_tracking_number')  $number  = $m['value'];
    }
    if ($number === '') continue;

    $result = call17Track($carrier, $number, $apiKey);
    list($type, $status, $time, $desc) = parseEvent($result);
    if ($type === null) continue;

    // Update order status when delivered
    if ($type === 'delivered') {
        wooRequest("/wp-json/wc/v3/orders/{$o['id']}", 'PUT', ['status' => 'delivered']);
    }

    // check for delay beyond threshold
    if ($time) {
        try {
            $dt = new DateTime($time);
            $days = $now->diff($dt)->days;
            if ($days > $threshold) {
                $delayed[] = [
                    'order_id' => $o['id'],
                    'days_since_event' => $days,
                    'status' => $status,
                    'last_event' => $desc,
                    'timestamp' => $time
                ];

                $alertEmail = getenv('ALERT_EMAIL');
                if ($alertEmail) {
                    @mail(
                        $alertEmail,
                        "Shipment Delay for Order #{$o['id']}",
                        "Order #{$o['id']} delayed {$days} days. Last event: {$desc}"
                    );
                }
            }
        } catch (Exception $e) {}
    }

    $events[] = [
        'order_id'   => $o['id'],
        'event_type' => $type,
        'status'     => $status,
        'timestamp'  => $time,
        'description'=> $desc
    ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode(['events' => $events, 'delayed' => $delayed]);
exit;
?>
