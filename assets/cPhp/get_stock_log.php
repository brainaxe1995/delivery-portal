<?php
require_once __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');
$id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
if (!$id) { echo json_encode([]); exit; }
$stmt = $db->prepare('SELECT change_qty, reason, timestamp FROM stock_log WHERE product_id = :p ORDER BY timestamp DESC');
$stmt->bindValue(':p', $id, SQLITE3_INTEGER);
$res = $stmt->execute();
$rows = [];
while ($row = $res->fetchArray(SQLITE3_ASSOC)) { $rows[] = $row; }
 echo json_encode($rows);
?>
