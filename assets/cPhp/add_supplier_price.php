<?php
require_once __DIR__ . '/config/bootstrap.php';
// portal/assets/cPhp/add_supplier_price.php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');
$data = json_decode(file_get_contents('php://input'), true) ?: $_POST;

$supplier = trim($data['supplier'] ?? '');
$product  = trim($data['product'] ?? '');
$price    = isset($data['price']) ? floatval($data['price']) : null;
$bulk     = isset($data['bulk_price']) ? floatval($data['bulk_price']) : null;
$date     = $data['effective_date'] ?? date('Y-m-d');

if (!$supplier || !$product || $price === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing supplier, product or price']);
    exit;
}

$stmt = $db->prepare('INSERT INTO supplier_prices (supplier,product,price,bulk_price,effective_date) VALUES (:s,:p,:pr,:b,:d)');
$stmt->bindValue(':s', $supplier);
$stmt->bindValue(':p', $product);
$stmt->bindValue(':pr', $price);
$stmt->bindValue(':b', $bulk);
$stmt->bindValue(':d', $date);
$stmt->execute();

echo json_encode(['status' => 'ok']);
?>
