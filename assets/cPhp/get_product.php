<?php
require_once __DIR__ . '/master-api.php';

function callWooAPI($baseUrl, $endpoint, $ck, $cs) {
    $url = rtrim($baseUrl, '/') . $endpoint;
    $ch  = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Basic ' . base64_encode("$ck:$cs"),
        'Content-Type: application/json'
    ]);
    $resp = curl_exec($ch);
    if (curl_errno($ch)) {
        http_response_code(500);
        echo json_encode(['error' => curl_error($ch)]);
        exit;
    }
    curl_close($ch);
    return $resp;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    http_response_code(400);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'Missing product ID']);
    exit;
}

$endpoint = "/wp-json/wc/v3/products/{$id}?context=edit";
header('Content-Type: application/json; charset=utf-8');

// Fetch product from WooCommerce
$raw = callWooAPI($store_url, "/wp-json/wc/v3/products/{$id}", $consumer_key, $consumer_secret);
$product = json_decode($raw, true);

// Attach local restock_eta value
$etaFile = __DIR__ . '/../data/restock_eta.json';
if (file_exists($etaFile)) {
    $data = json_decode(file_get_contents($etaFile), true);
    foreach ($data as $e) {
        if (isset($e['id']) && (int)$e['id'] === $id) {
            $product['restock_eta'] = $e['restock_eta'] ?? null;
            break;
        }
    }
}

echo json_encode($product);


$json = callWooAPI($store_url, "/wp-json/wc/v3/products/{$id}", $consumer_key, $consumer_secret);
$product = json_decode($json, true);

if (is_array($product)) {
    if (($product['type'] ?? '') === 'variable') {
        $varResp = callWooAPI(
            $store_url,
            "/wp-json/wc/v3/products/{$id}/variations?per_page=100",
            $consumer_key,
            $consumer_secret
        );
        $vars = json_decode($varResp, true);
        if (is_array($vars)) {
            $product['variant_attributes'] = array_map(function($v){
                return $v['attributes'];
            }, $vars);
        }
    }
    // extract custom metadata
    if (!empty($product['meta_data']) && is_array($product['meta_data'])) {
        foreach ($product['meta_data'] as $m) {
            if ($m['key'] === '_packaging_info_url') {
                $product['packaging_info_url'] = $m['value'];
            }
            if ($m['key'] === '_safety_sheet_url') {
                $product['safety_sheet_url'] = $m['value'];
            }
        }
    }
    echo json_encode($product);
    exit;
}

echo $json;

echo callWooAPI($store_url, $endpoint, $consumer_key, $consumer_secret);


exit;
?>
