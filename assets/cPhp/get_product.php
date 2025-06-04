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

header('Content-Type: application/json; charset=utf-8');
echo callWooAPI($store_url, "/wp-json/wc/v3/products/{$id}", $consumer_key, $consumer_secret);
exit;
?>
