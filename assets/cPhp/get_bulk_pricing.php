<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');
$productId = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
if ($productId) {
    $stmt = $db->prepare('SELECT * FROM price_tiers WHERE product_id = :pid ORDER BY min_qty');
    $stmt->bindValue(':pid', $productId, SQLITE3_INTEGER);
    $res = $stmt->execute();
} else {
    $res = $db->query('SELECT * FROM price_tiers ORDER BY product_id, min_qty');
}
$rows = [];
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $rows[] = $row;
}
 echo json_encode($rows);
?>
