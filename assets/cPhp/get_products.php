<?php
require_once __DIR__ . '/master-api.php';  // loads $store_url, $consumer_key, $consumer_secret

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
    if (preg_match('/^X-WP-TotalPages:\s*(\d+)/mi', $header, $m)) {
        header('X-My-TotalPages: ' . $m[1]);
    }
    return $body;
}

$page     = isset($_GET['page'])     ? (int)$_GET['page']     : 1;
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
$endpoint = "/wp-json/wc/v3/products?page={$page}&per_page={$per_page}";

$json = callWooAPI($store_url, $endpoint, $consumer_key, $consumer_secret);
$products = json_decode($json, true);

if (is_array($products)) {
    foreach ($products as &$p) {
        if (($p['type'] ?? '') === 'variable') {
            $varResp = callWooAPI(
                $store_url,
                "/wp-json/wc/v3/products/{$p['id']}/variations?per_page=100",
                $consumer_key,
                $consumer_secret
            );
            $vars = json_decode($varResp, true);
            if (is_array($vars)) {
                $p['variant_attributes'] = array_map(function($v){
                    return $v['attributes'];
                }, $vars);
            }
        }
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($products);
    exit;
}

header('Content-Type: application/json; charset=utf-8');
echo $json;
exit;
