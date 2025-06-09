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
$id = isset($_GET['refund_id']) ? (int)$_GET['refund_id'] : 0;
if (!$id) { echo json_encode([]); exit; }
$stmt = $db->prepare('SELECT user_id, comment, timestamp FROM refund_comments WHERE refund_id = :r ORDER BY timestamp ASC');
$stmt->bindValue(':r', $id, SQLITE3_INTEGER);
$res = $stmt->execute();
$rows = [];
while ($row = $res->fetchArray(SQLITE3_ASSOC)) { $rows[] = $row; }
 echo json_encode($rows);
?>
