<?php
// assets/cPhp/submit_product_request.php
header('Content-Type: application/json; charset=utf-8');
$file = __DIR__ . '/../data/product_requests.json';
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
$raw = file_get_contents('php://input');
$payload = json_decode($raw, true) ?: $_POST;
$type = isset($payload['type']) ? $payload['type'] : '';
$product = isset($payload['product']) ? trim($payload['product']) : '';
$price = isset($payload['proposed_price']) ? (float)$payload['proposed_price'] : 0;
$reason = isset($payload['reason']) ? trim($payload['reason']) : '';
if(!$type || !$product){
    http_response_code(400);
    echo json_encode(['error'=>'Missing required fields']);
    exit;
}
$id = count($data) ? max(array_column($data, 'id')) + 1 : 1;
$data[] = [
    'id'=>$id,
    'type'=>$type,
    'product'=>$product,
    'proposed_price'=>$price,
    'reason'=>$reason,
    'status'=>'Pending',
    'date'=>date('Y-m-d')
];
file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
echo json_encode(['success'=>true,'id'=>$id]);
exit;
?>
