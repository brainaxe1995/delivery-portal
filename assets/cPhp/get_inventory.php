<?php
require_once __DIR__ . '/config/bootstrap.php';
// portal/assets/cPhp/get_inventory.php

require_once __DIR__ . '/master-api.php';  // loads $store_url, $consumer_key, $consumer_secret
require_once __DIR__ . '/db.php';

function callWoo($base, $endpoint, $ck, $cs) {
    $url = rtrim($base, '/') . $endpoint;
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

    // Re-emit total-pages for pagination
    if (preg_match('/^X-WP-TotalPages:\s*(\d+)/mi', $header, $m)) {
        header('X-My-TotalPages: ' . $m[1]);
    }
    return $body;
}

$page     = isset($_GET['page'])     ? (int)$_GET['page']     : 1;
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;

$endpoint = "/wp-json/wc/v3/products?page={$page}&per_page={$per_page}";

// Fetch raw WooCommerce product list
$rawJson = callWoo($store_url, $endpoint, $consumer_key, $consumer_secret);

// Decode and slim
$products = json_decode($rawJson, true);
$inventory = [];
foreach ($products as $p) {
    $sid = (int)$p['id'];
    $row = $db->querySingle('SELECT safety_stock,reorder_threshold FROM inventory_settings WHERE product_id='.$sid, true) ?: [];
    $safety  = isset($row['safety_stock']) ? (int)$row['safety_stock'] : null;
    $reorder = isset($row['reorder_threshold']) ? (int)$row['reorder_threshold'] : null;
    $qty = $p['stock_quantity'] ?? null;
    $low = ($reorder !== null && $qty !== null && $qty <= $reorder);
    $inventory[] = [
        'id'                => $sid,
        'name'              => $p['name'],
        'stock_quantity'    => $qty,
        'stock_status'      => $p['stock_status'],
        'safety_stock'      => $safety,
        'reorder_threshold' => $reorder,
        'low_stock'         => $low
    ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($inventory);
exit;
