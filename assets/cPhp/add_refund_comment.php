<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/db.php';
// Ensure refund_comments table exists if using WordPress DB
if (isset($GLOBALS['wpdb'])) {
    global $wpdb;
    $table = $wpdb->prefix . 'refund_comments';
    if (! $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table))) {
        $wpdb->query("
            CREATE TABLE {$table} (
                id INT AUTO_INCREMENT PRIMARY KEY,
                refund_id BIGINT NOT NULL,
                user_id BIGINT NOT NULL,
                comment TEXT NOT NULL,
                timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }
}
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
