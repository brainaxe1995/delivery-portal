<?php
// portal/assets/cPhp/add_product_request.php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');
$raw  = file_get_contents('php://input');
$data = json_decode($raw, true) ?: $_POST;

$supplier = trim($data['supplier'] ?? '');
$product  = trim($data['product'] ?? '');
$desc     = trim($data['description'] ?? '');

if (!$supplier || !$product) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing supplier or product']);
    exit;
}

$stmt = $db->prepare('INSERT INTO product_requests (supplier, product, description, status, notes) VALUES (:s,:p,:d,"new","")');
$stmt->bindValue(':s', $supplier, SQLITE3_TEXT);
$stmt->bindValue(':p', $product, SQLITE3_TEXT);
$stmt->bindValue(':d', $desc, SQLITE3_TEXT);
$stmt->execute();

echo json_encode(['id' => $db->lastInsertRowID()]);
?>
