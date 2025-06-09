<?php
require_once __DIR__ . '/config/bootstrap.php';
// assets/cPhp/update_order.php

require_once(__DIR__ . '/master-api.php'); // loads $store_url, $consumer_key, $consumer_secret

// 1) Decode JSON (for JS fetch) or fall back to $_POST (for FormData)
$raw   = file_get_contents('php://input');
$input = json_decode($raw, true);
if (!is_array($input) || empty($input)) {
    $input = $_POST;
}

header('Content-Type: application/json; charset=utf-8');

// 2) Validate required fields
if (empty($input['order_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing order_id']);
    exit;
}

$order_id = (int) $input['order_id'];
$update   = [];

// 3) Optional tracking code update
if (!empty($input['tracking_code'])) {
    $tracking_no = trim($input['tracking_code']);
    $carrier     = trim($input['carrier_slug'] ?? '');

    $meta = [ ['key' => '_wot_tracking_number', 'value' => $tracking_no] ];
    if ($carrier !== '') {
        $meta[] = ['key' => '_wot_tracking_carrier', 'value' => $carrier];
    }

    $update['meta_data'] = $meta;
}

// 4) Optional status update
if (!empty($input['status'])) {
    $update['status'] = trim($input['status']);
}

if (empty($update)) {
    http_response_code(400);
    echo json_encode(['error' => 'No update parameters provided']);
    exit;
}

// 5) Send PUT to WooCommerce REST API
$endpoint = "/wp-json/wc/v3/orders/{$order_id}";
$ch = curl_init(rtrim($store_url, '/') . $endpoint);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$consumer_key:$consumer_secret");
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($update));

$resp   = curl_exec($ch);
$code   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// 6) Return the WooCommerce API response
http_response_code($code);
echo $resp;
exit;
