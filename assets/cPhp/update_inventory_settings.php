<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');
$raw = file_get_contents('php://input');
if ($raw === '' && PHP_SAPI === 'cli') {
    $raw = stream_get_contents(STDIN);
}
$data = json_decode($raw, true) ?: $_POST;
$product = isset($data['product_id']) ? (int)$data['product_id'] : 0;
$safety = isset($data['safety_stock']) ? (int)$data['safety_stock'] : null;
$reorder = isset($data['reorder_threshold']) ? (int)$data['reorder_threshold'] : null;
if (!$product) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing product_id']);
    exit;
}
$stmt = $db->prepare('REPLACE INTO inventory_settings (product_id,safety_stock,reorder_threshold) VALUES (:p,:s,:r)');
$stmt->bindValue(':p', $product, SQLITE3_INTEGER);
$stmt->bindValue(':s', $safety, SQLITE3_INTEGER);
$stmt->bindValue(':r', $reorder, SQLITE3_INTEGER);
$stmt->execute();

echo json_encode(['success' => true]);
?>
