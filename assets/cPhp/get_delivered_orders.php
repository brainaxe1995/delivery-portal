<?php
// portal/assets/cPhp/get_delivered_order.php
require_once __DIR__ . '/master-api.php';

function callWooAPI($baseUrl, $endpoint, $ck, $cs) {
    $url = rtrim($baseUrl, '/') . $endpoint;
    $ch  = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER,         true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Basic ' . base64_encode("$ck:$cs"),
        'Content-Type: application/json',
    ]);
    $raw = curl_exec($ch);
    if (curl_errno($ch)) {
        http_response_code(500);
        echo json_encode(['error'=>curl_error($ch)]);
        exit;
    }
    $hsize  = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($raw, 0, $hsize);
    $body   = substr($raw, $hsize);
    curl_close($ch);

    // Re-emit TotalPages header for our pagination.js
    if (preg_match('/^X-WP-TotalPages:\s*(\d+)/mi', $header, $m)) {
        header('X-My-TotalPages: ' . $m[1]);
    }
    return $body;
}

// page & per_page from GET
$page      = isset($_GET['page'])     ? (int)$_GET['page']     : 1;
$per_page  = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
$order_id  = isset($_GET['order_id']) ? trim($_GET['order_id']) : '';

// *** IMPORTANT: use your custom “delivered” slug here ***
$endpoint = "/wp-json/wc/v3/orders?"
          . "status=delivered"
          . "&page={$page}&per_page={$per_page}";
if ($order_id !== '') {
    $endpoint .= '&search=' . urlencode($order_id);
}

header('Content-Type: application/json; charset=utf-8');
echo callWooAPI($store_url, $endpoint, $consumer_key, $consumer_secret);
exit;
