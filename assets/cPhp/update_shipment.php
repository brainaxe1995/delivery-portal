<?php
require_once __DIR__ . '/config/bootstrap.php';
// portal/assets/cPhp/update_shipment.php
require_once __DIR__ . '/master-api.php';

header('Content-Type: application/json; charset=utf-8');

$ctype = $_SERVER['CONTENT_TYPE'] ?? '';

// ---------------------------------------------------------------------
// JSON request - inline update from shipments.js
// ---------------------------------------------------------------------
if (stripos($ctype, 'application/json') !== false) {
    $raw  = file_get_contents('php://input');
    $data = json_decode($raw, true);

    if (!is_array($data)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON payload']);
        exit;
    }

    $orderId  = isset($data['order_id']) ? (int)$data['order_id'] : 0;
    $provider = trim($data['provider'] ?? '');
    $tracking = trim($data['tracking_no'] ?? '');
    $eta      = trim($data['eta'] ?? '');

    if (!$orderId) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing order_id']);
        exit;
    }

    $meta = [];
    if ($tracking !== '')  $meta[] = ['key' => '_wot_tracking_number',  'value' => $tracking];
    if ($provider !== '')  $meta[] = ['key' => '_wot_tracking_carrier', 'value' => $provider];
    if ($eta !== '')       $meta[] = ['key' => '_wot_eta',              'value' => $eta];

    $payload = ['meta_data' => $meta];

    $ch = curl_init(rtrim($store_url, '/') . "/wp-json/wc/v3/orders/{$orderId}");
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST  => 'PUT',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERPWD        => "$consumer_key:$consumer_secret",
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS     => json_encode($payload)
    ]);
    $resp   = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status >= 200 && $status < 300) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code($status ?: 500);
        echo json_encode([
            'error'   => 'WooCommerce API error',
            'details' => $resp
        ]);
    }
    exit;
}

// ---------------------------------------------------------------------
// CSV upload - manifest file
// ---------------------------------------------------------------------
if (empty($_FILES['manifest']) || $_FILES['manifest']['error']) {
    http_response_code(400);
    echo json_encode(['error' => 'No file']);
    exit;
}

$tmp = fopen($_FILES['manifest']['tmp_name'], 'r');
if (!$tmp) {
    http_response_code(500);
    echo json_encode(['error' => 'Upload failed']);
    exit;
}

// Skip header if present
$first = fgetcsv($tmp);

while (($row = fgetcsv($tmp)) !== false) {
    list($order_id, $provider, $tracking, $eta) = $row;

    $payload = ['meta_data' => [
        ['key' => '_wot_tracking_number',  'value' => $tracking],
        ['key' => '_wot_tracking_carrier', 'value' => $provider],
        ['key' => '_wot_eta',              'value' => $eta]
    ]];

    $ch = curl_init(rtrim($store_url, '/') . "/wp-json/wc/v3/orders/{$order_id}");
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST  => 'PUT',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERPWD        => "$consumer_key:$consumer_secret",
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS     => json_encode($payload)
    ]);
    $resp   = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($status < 200 || $status >= 300) {
        http_response_code($status ?: 500);
        echo json_encode([
            'error'   => 'WooCommerce API error',
            'details' => $resp,
            'order_id'=> $order_id
        ]);
        exit;
    }
}

echo json_encode(['success' => true]);
exit;
?>
