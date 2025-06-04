<?php
header('Content-Type: application/json; charset=utf-8');
$file = __DIR__ . '/../data/invoices.json';
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
$raw = file_get_contents('php://input');
$payload = json_decode($raw, true) ?: $_POST;
$items = isset($payload['items']) && is_array($payload['items']) ? $payload['items'] : [];
if(!$items){
    http_response_code(400);
    echo json_encode(['error' => 'Missing items']);
    exit;
}
$customer = $items[0]['customerName'] ?? '';
$amount   = 0;
foreach($items as $it){
    $amount += isset($it['totalCost']) ? (float)$it['totalCost'] : 0;
}
$status = 'Pending';
$date   = date('Y-m-d');
$id = count($data) ? max(array_column($data, 'id')) + 1 : 1;
$data[] = [
    'id' => $id,
    'customer' => $customer,
    'amount' => $amount,
    'status' => $status,
    'date' => $date,
    'items' => $items
];
file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
echo json_encode(['success'=>true,'id'=>$id]);
exit;
?>
