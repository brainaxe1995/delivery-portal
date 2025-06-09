<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');
$id = isset($_GET['refund_id']) ? (int)$_GET['refund_id'] : 0;
if (!$id) { echo json_encode([]); exit; }
$stmt = $db->prepare('SELECT user_id, comment, timestamp FROM refund_comments WHERE refund_id = :r ORDER BY timestamp ASC');
$stmt->bindValue(':r', $id, SQLITE3_INTEGER);
$res = $stmt->execute();
$rows = [];
while ($row = $res->fetchArray(SQLITE3_ASSOC)) { $rows[] = $row; }
 echo json_encode($rows);
?>
