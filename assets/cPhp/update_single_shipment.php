<?php
// assets/cPhp/update_single_shipment.php
// Update tracking information for a single order via JSON payload

require_once __DIR__ . '/master-api.php';

header('Content-Type: application/json; charset=utf-8');

$raw  = file_get_contents('php://input');
$data = json_decode($raw, true) ?: $_POST;

$order_id = isset($data['order_id']) ? (int)$data['order_id'] : 0;
if (!$order_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing order_id']);
    exit;
}

$meta = [];
if (isset($data['provider'])) {
    $meta[] = ['key' => '_wot_tracking_carrier', 'value' => $data['provider']];
}
if (isset($data['tracking_no'])) {
    $meta[] = ['key' => '_wot_tracking_number', 'value' => $data['tracking_no']];
}
if (isset($data['eta'])) {
    $meta[] = ['key' => '_wot_eta', 'value' => $data['eta']];
}

if (empty($meta)) {
    http_response_code(400);
    echo json_encode(['error' => 'No fields to update']);
    exit;
}

$payload = ['meta_data' => $meta];
$ch = curl_init(rtrim($store_url, '/') . "/wp-json/wc/v3/orders/{$order_id}");
curl_setopt_array($ch, [
    CURLOPT_CUSTOMREQUEST  => 'PUT',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERPWD        => "$consumer_key:$consumer_secret",
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS     => json_encode($payload)
]);
$response = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($code);
echo $response !== false ? $response : json_encode(['error' => 'Request failed']);
exit;
?>
