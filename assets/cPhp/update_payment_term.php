<?php
require_once __DIR__ . '/config/bootstrap.php';
// portal/assets/cPhp/update_payment_term.php
$file = __DIR__ . '/../data/payment_terms.json';
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
$raw = file_get_contents('php://input');
$payload = json_decode($raw, true) ?: $_POST;
$id = isset($payload['id']) ? (int)$payload['id'] : 0;
$name = isset($payload['name']) ? trim($payload['name']) : '';
$desc = isset($payload['description']) ? trim($payload['description']) : '';
if (!$name) {
    http_response_code(400);
    echo json_encode(['error'=>'Missing name']);
    exit;
}
if ($id) {
    foreach ($data as &$t) {
        if ($t['id'] == $id) {
            $t['name'] = $name;
            $t['description'] = $desc;
            break;
        }
    }
    unset($t);
} else {
    $id = count($data) ? max(array_column($data,'id')) + 1 : 1;
    $data[] = ['id'=>$id,'name'=>$name,'description'=>$desc];
}
file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['success'=>true,'id'=>$id]);
exit;
?>
