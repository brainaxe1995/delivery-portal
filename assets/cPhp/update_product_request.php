<?php
// assets/cPhp/update_product_request.php
header('Content-Type: application/json; charset=utf-8');
$file = __DIR__ . '/../data/product_requests.json';
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
$raw = file_get_contents('php://input');
$payload = json_decode($raw, true) ?: $_POST;
$id = isset($payload['id']) ? (int)$payload['id'] : 0;
$status = isset($payload['status']) ? $payload['status'] : '';
if(!$id || !in_array($status,['Approved','Rejected'])){
    http_response_code(400);
    echo json_encode(['error'=>'Invalid request']);
    exit;
}
foreach($data as &$req){
    if($req['id']==$id){
        $req['status'] = $status;
        break;
    }
}
unset($req);
file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
echo json_encode(['success'=>true]);
exit;
?>
