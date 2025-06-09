<?php
require_once __DIR__ . '/config/bootstrap.php';
// portal/assets/cPhp/add_lead_time.php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');
$data = json_decode(file_get_contents('php://input'), true) ?: $_POST;

$id       = isset($data['id']) ? intval($data['id']) : 0;
$product  = trim($data['product'] ?? '');
$supplier = trim($data['supplier'] ?? '');
$lead     = isset($data['lead_time']) ? intval($data['lead_time']) : null;

if (!$product || !$supplier || $lead === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing product, supplier or lead time']);
    exit;
}

$sql = $id ?
    'UPDATE lead_times SET product=:p,supplier=:s,lead_time=:l,last_updated=CURRENT_TIMESTAMP WHERE id=:id'
    : 'INSERT INTO lead_times (product,supplier,lead_time) VALUES (:p,:s,:l)';
$stmt = $db->prepare($sql);
$stmt->bindValue(':p', $product);
$stmt->bindValue(':s', $supplier);
$stmt->bindValue(':l', $lead, SQLITE3_INTEGER);
if ($id) $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$stmt->execute();

echo json_encode(['status' => 'ok']);
?>
