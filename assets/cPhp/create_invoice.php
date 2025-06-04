<?php
header('Content-Type: application/json; charset=utf-8');
$file = __DIR__ . '/../data/invoices.json';
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
$raw = file_get_contents('php://input');
$payload = json_decode($raw, true) ?: $_POST;
$customer = trim($payload['customer'] ?? '');
$amount   = isset($payload['amount']) ? (float)$payload['amount'] : 0;
$status   = trim($payload['status'] ?? '');
$date     = trim($payload['date'] ?? '');
if($customer === '' || $status === '' || $date === ''){
    http_response_code(400);
    echo json_encode(['error' => 'Missing fields']);
    exit;
}
$id = count($data) ? max(array_column($data, 'id')) + 1 : 1;
$data[] = [
    'id' => $id,
    'customer' => $customer,
    'amount' => $amount,
    'status' => $status,
    'date' => $date
];
file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
echo json_encode(['success'=>true,'id'=>$id]);
exit;
?>
