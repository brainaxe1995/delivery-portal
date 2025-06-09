<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/master-api.php';

$url = rtrim($store_url, '/') . '/wp-json/wc/v3/orders?status=on-hold&per_page=1';
$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER         => true,
    CURLOPT_USERPWD        => "$consumer_key:$consumer_secret",
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json']
]);
$response = curl_exec($ch);
if ($response === false) {
    http_response_code(500);
    echo json_encode(['error' => curl_error($ch)]);
    exit;
}
$hsize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($response, 0, $hsize);
curl_close($ch);
$count = 0;
if (preg_match('/^X-WP-Total:\s*(\d+)/mi', $header, $m)) {
    $count = (int)$m[1];
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['count' => $count]);
exit;
?>
