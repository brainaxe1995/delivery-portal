<?php
// portal/assets/cPhp/update_product.php

require_once(__DIR__ . '/master-api.php');  // defines $store_url, $consumer_key, $consumer_secret

// 1) Decode JSON payload (or fallback to $_POST)
$raw  = file_get_contents('php://input');
$data = json_decode($raw, true) ?: $_POST;

// 2) Validate product ID
$id = isset($data['id']) ? (int)$data['id'] : 0;
if (!$id) {
    http_response_code(400);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error'=>'Missing product ID']);
    exit;
}

// 3) Prepare update fields
$fields = [];
if (isset($data['price']))  $fields['regular_price'] = (string)$data['price'];
if (isset($data['stock']))  $fields['stock_quantity'] = (int)$data['stock'];

if (isset($data['status'])) $fields['stock_status']   = $data['status'];

$restock_eta = array_key_exists('restock_eta', $data) ? trim($data['restock_eta']) : null;

if (isset($data['moq']))   $fields['meta_data'] = [['key' => 'moq', 'value' => (int)$data['moq']]];

if (isset($data['status'])) {
    $allowedStatuses = ['instock', 'outofstock', 'discontinued'];
    if (!in_array($data['status'], $allowedStatuses, true)) {
        http_response_code(400);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => 'Invalid status']);
        exit;
    }
    $fields['stock_status'] = $data['status'];
}


$meta = [];
if (isset($data['packaging_info_url'])) {
    $meta[] = ['key' => '_packaging_info_url', 'value' => $data['packaging_info_url']];
}
if (isset($data['safety_sheet_url'])) {
    $meta[] = ['key' => '_safety_sheet_url', 'value' => $data['safety_sheet_url']];
}
if ($meta) {
    $fields['meta_data'] = $meta;
}


if (empty($fields) && $restock_eta === null) {
    http_response_code(400);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error'=>'No fields to update']);
    exit;
}

// Helper: perform Woo API requests
function wc_request($method, $endpoint, $payload = null) {
    global $store_url, $consumer_key, $consumer_secret;
    $url = rtrim($store_url, '/') . $endpoint;
    $ch  = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$consumer_key:$consumer_secret");
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    if ($method === 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    }
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [$code, json_decode($resp, true)];
}

// Save restock ETA to local JSON store
function save_eta($product_id, $eta) {
    $file = __DIR__ . '/../data/restock_eta.json';
    $list = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    $map = [];
    foreach ($list as $row) {
        if (isset($row['id'])) {
            $map[$row['id']] = $row['restock_eta'] ?? null;
        }
    }
    if ($eta === '' || $eta === null) {
        unset($map[$product_id]);
    } else {
        $map[$product_id] = $eta;
    }
    $new = [];
    foreach ($map as $id => $val) {
        $new[] = ['id'=>$id, 'restock_eta'=>$val];
    }
    file_put_contents($file, json_encode($new, JSON_PRETTY_PRINT));
}
// 4) Fetch the product to know its type & variations
list($code, $product) = wc_request('GET', "/wp-json/wc/v3/products/{$id}");
if ($code !== 200 || !isset($product['type'])) {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($product);
    exit;
}

// 5) If variable → update each variation under the product
$responses = [];
if ($product['type'] === 'variable' && !empty($product['variations'])) {
    foreach ($product['variations'] as $varId) {
        $variationEndpoint = "/wp-json/wc/v3/products/{$id}/variations/{$varId}";
        list($c, $resp) = wc_request('PUT', $variationEndpoint, $fields);
        $responses[$varId] = ['code'=>$c, 'response'=>$resp];
    }
    if ($restock_eta !== null) save_eta($id, $restock_eta);
    http_response_code(200);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['updated_variations'=>$responses]);
    exit;
}

// 6) Otherwise (simple product) → update the product itself
list($c2, $resp2) = wc_request('PUT', "/wp-json/wc/v3/products/{$id}", $fields);
if ($restock_eta !== null) save_eta($id, $restock_eta);
http_response_code($c2);
header('Content-Type: application/json; charset=utf-8');
echo json_encode($resp2);
exit;
