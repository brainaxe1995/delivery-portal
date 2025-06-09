<?php
require_once __DIR__ . '/config/bootstrap.php';
header('Content-Type: application/json; charset=utf-8');
$file = __DIR__ . '/../data/invoices.json';
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
$raw = file_get_contents('php://input');
$payload = json_decode($raw, true) ?: $_POST;
$id = isset($payload['id']) ? (int)$payload['id'] : 0;
if(!$id){
    http_response_code(400);
    echo json_encode(['error'=>'Missing id']);
    exit;
}
$found = false;
foreach($data as $k=>$v){
    if($v['id'] == $id){
        $found = true;
        unset($data[$k]);
        break;
    }
}
if(!$found){
    http_response_code(404);
    echo json_encode(['error'=>'Not found']);
    exit;
}
$data = array_values($data);
file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
echo json_encode(['success'=>true]);
exit;
?>
