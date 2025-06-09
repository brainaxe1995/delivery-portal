<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/db.php';
// Ensure stock_log table exists if using WordPress DB
if (isset($GLOBALS['wpdb'])) {
    global $wpdb;
    $table = $wpdb->prefix . 'stock_log';
    if (! $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table))) {
        $wpdb->query("
            CREATE TABLE {$table} (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id BIGINT NOT NULL,
                change_qty INT NOT NULL,
                reason TEXT NOT NULL,
                timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }
}
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
