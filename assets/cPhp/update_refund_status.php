<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/master-api.php';
header('Content-Type: application/json; charset=utf-8');

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input) || empty($input)) {
    $input = $_POST;
}

$refund_id = isset($input['refund_id']) ? (int)$input['refund_id'] : 0;
$status    = $input['status'] ?? '';
if (!$refund_id || $status==='') {
    http_response_code(400);
    echo json_encode(['error' => 'Missing refund_id or status']);
    exit;
}

$endpoint = "/wp-json/wc/v3/refunds/{$refund_id}";
$data = ['status' => $status];

$ch = curl_init(rtrim($store_url,'/').$endpoint);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$consumer_key:$consumer_secret");
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
$resp = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// handle file upload
if (!empty($_FILES['proof']['tmp_name'])) {
    $dir = __DIR__ . '/../uploads/refunds';
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    $name = basename($_FILES['proof']['name']);
    move_uploaded_file($_FILES['proof']['tmp_name'], "$dir/$refund_id-$name");
}

http_response_code($code);
echo $resp;
?>
