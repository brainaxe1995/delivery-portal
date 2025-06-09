<?php
require_once __DIR__ . '/config/bootstrap.php';
// Enable detailed errors only when DEBUG environment variable is truthy
if (getenv('DEBUG')) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

require_once __DIR__ . '/master-api.php';  // loads $store_url, $consumer_key, $consumer_secret

// Pagination inputs
$page      = isset($_GET['page'])     ? (int)$_GET['page']     : 1;
$per_page  = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
$order_id  = isset($_GET['order_id']) ? trim($_GET['order_id']) : '';

/**
 * Call WooCommerce REST API and re-emit X-My-TotalPages
 */
function callWoo($endpoint) {
    global $store_url, $consumer_key, $consumer_secret;
    $url = rtrim($store_url, '/') . $endpoint;
    $ch  = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER,         true);
    curl_setopt($ch, CURLOPT_USERPWD,        "$consumer_key:$consumer_secret");
    curl_setopt($ch, CURLOPT_HTTPHEADER,     ['Content-Type: application/json']);

    $raw = curl_exec($ch);
    if (curl_errno($ch)) {
        http_response_code(500);
        echo json_encode(['error' => curl_error($ch)]);
        exit;
    }

    $hsize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($raw, 0, $hsize);
    $body   = substr($raw, $hsize);
    curl_close($ch);

    // Re-emit total pages
    if (preg_match('/^X-WP-TotalPages:\s*(\d+)/mi', $header, $m)) {
        header('X-My-TotalPages: ' . $m[1]);
    }

    return json_decode($body, true);
}

// Fetch orders
$endpoint = "/wp-json/wc/v3/orders?page={$page}&per_page={$per_page}";
if ($order_id !== '') {
    $endpoint .= '&search=' . urlencode($order_id);
}
$orders   = callWoo($endpoint);

header('Content-Type: application/json; charset=utf-8');
$out = [];
foreach ($orders as $o) {
    // extract meta
    $prov = $track = $eta = '';
    foreach ($o['meta_data'] as $m) {
        if ($m['key'] === '_wot_tracking_carrier') $prov = $m['value'];
        if ($m['key'] === '_wot_tracking_number')  $track = $m['value'];
        if ($m['key'] === '_wot_eta')              $eta   = $m['value'];
    }
    $out[] = [
      'order_id'    => $o['id'],
      'total'       => $o['total'],
      'provider'    => $prov,
      'tracking_no' => $track,
      'eta'         => $eta,
      'status'      => $o['status'],
      'origin'      => $o['shipping']['country'] ?? '',
      'last_update' => $o['date_modified'],
    ];
}

echo json_encode($out);
exit;
