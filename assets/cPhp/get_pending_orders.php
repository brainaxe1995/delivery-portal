<?php
// get_pending_orders.php
//
// Fetches WooCommerce orders with status=pending ("New / Pending Orders")
// and re-emits the X-My-TotalPages header so JS can paginate correctly.

require_once(__DIR__ . '/master-api.php'); // loads $store_url, $consumer_key, $consumer_secret

function callWooAPI($baseUrl, $endpoint, $ck, $cs) {
    $url = rtrim($baseUrl, '/') . $endpoint;
    $ch  = curl_init($url);

    // Return header + body so we can extract pagination info
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER,         true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Authorization: Basic ' . base64_encode("$ck:$cs"),
      'Content-Type: application/json',
    ]);

    $raw = curl_exec($ch);

    if (curl_errno($ch)) {
        http_response_code(500);
        echo json_encode(['error' => curl_error($ch)]);
        exit;
    }

    // Split header and body
    $hsize  = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($raw, 0, $hsize);
    $body   = substr($raw, $hsize);
    curl_close($ch);

    // Extract and re-emit WooCommerce's total pages header
    if (preg_match('/^X-WP-TotalPages:\s*(\d+)/mi', $header, $matches)) {
        header('X-My-TotalPages: ' . $matches[1]);
    }

    return $body;
}

// Pagination inputs
$page      = isset($_GET['page'])     ? (int) $_GET['page']     : 1;
$per_page  = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 20;
$order_id  = isset($_GET['order_id']) ? trim($_GET['order_id']) : '';

// Fetch only "pending" orders (your New/Pending bucket)
$endpoint = "/wp-json/wc/v3/orders?status=pending&page={$page}&per_page={$per_page}";
if ($order_id !== '') {
    $endpoint .= '&search=' . urlencode($order_id);
}

// — Fetch raw orders JSON —
$body   = callWooAPI($store_url, $endpoint, $consumer_key, $consumer_secret);
$orders = json_decode($body, true);

// — Inject tracking number from the Orders Tracking plugin’s meta_data —
foreach ($orders as &$o) {
    $o['tracking_no'] = '';
    if (!empty($o['meta_data'])) {
        foreach ($o['meta_data'] as $m) {
            if ($m['key'] === '_wot_tracking_number') {
                $o['tracking_no'] = $m['value'];
                break;
            }
        }
    }
}
unset($o); // break reference

// — Output enriched JSON for your JS to consume —
header('Content-Type: application/json; charset=utf-8');
echo json_encode($orders);
exit;
