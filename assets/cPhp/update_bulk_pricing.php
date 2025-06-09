<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');
$raw = file_get_contents('php://input');
if ($raw === '' && PHP_SAPI === 'cli') {
    $raw = stream_get_contents(STDIN);
}
$data = json_decode($raw, true) ?: $_POST;
$productId = isset($data['product_id']) ? (int)$data['product_id'] : 0;
$tiers = $data['tiers'] ?? null;
if (!$productId || !is_array($tiers)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid payload']);
    exit;
}
$db->exec('BEGIN');
$stmtDel = $db->prepare('DELETE FROM price_tiers WHERE product_id = :pid');
$stmtDel->bindValue(':pid', $productId, SQLITE3_INTEGER);
$stmtDel->execute();
$insert = $db->prepare('INSERT INTO price_tiers (product_id,min_qty,max_qty,unit_price) VALUES (:pid,:min,:max,:price)');
foreach ($tiers as $t) {
    $insert->bindValue(':pid', $productId, SQLITE3_INTEGER);
    $insert->bindValue(':min', intval($t['min_qty'] ?? 0), SQLITE3_INTEGER);
    $insert->bindValue(':max', intval($t['max_qty'] ?? 0), SQLITE3_INTEGER);
    $insert->bindValue(':price', floatval($t['unit_price'] ?? 0), SQLITE3_FLOAT);
    $insert->execute();
}
$db->exec('COMMIT');
echo json_encode(['success' => true]);
?>
