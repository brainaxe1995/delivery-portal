<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');
$data = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$id   = isset($data['id']) ? (int)$data['id'] : 0;
$bulk = isset($data['bulk_price']) ? floatval($data['bulk_price']) : null;
if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing id']);
    exit;
}
$stmt = $db->prepare('UPDATE supplier_prices SET bulk_price = :b WHERE id = :i');
$stmt->bindValue(':b', $bulk);
$stmt->bindValue(':i', $id, SQLITE3_INTEGER);
$stmt->execute();
if ($db->changes() === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Record not found']);
    exit;
}
echo json_encode(['success' => true]);
