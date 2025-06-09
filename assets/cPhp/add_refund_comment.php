<?php
require_once __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');
$raw = file_get_contents('php://input');
if ($raw === '' && PHP_SAPI === 'cli') {
    $raw = stream_get_contents(STDIN);
}
$data = json_decode($raw, true) ?: $_POST;
$refund = isset($data['refund_id']) ? (int)$data['refund_id'] : 0;
$user   = isset($data['user_id']) ? (int)$data['user_id'] : 0;
$comment= trim($data['comment'] ?? '');
if (!$refund || !$user || $comment==='') {
    http_response_code(400);
    echo json_encode(['error'=>'Invalid payload']);
    exit;
}
$stmt = $db->prepare('INSERT INTO refund_comments (refund_id,user_id,comment) VALUES (:r,:u,:c)');
$stmt->bindValue(':r', $refund, SQLITE3_INTEGER);
$stmt->bindValue(':u', $user, SQLITE3_INTEGER);
$stmt->bindValue(':c', $comment, SQLITE3_TEXT);
$stmt->execute();
 echo json_encode(['success'=>true,'id'=>$db->lastInsertRowID()]);
?>
