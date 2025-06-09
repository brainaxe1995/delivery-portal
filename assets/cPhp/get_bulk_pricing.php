<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/db.php';
// Ensure price_tiers table exists if using WordPress DB
if (isset($GLOBALS['wpdb'])) {
    global $wpdb;
    $table = $wpdb->prefix . 'price_tiers';
    if (! $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table))) {
        $wpdb->query("
            CREATE TABLE {$table} (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id BIGINT NOT NULL,
                min_qty INT NOT NULL,
                max_qty INT NOT NULL,
                unit_price DECIMAL(10,2) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }
}
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
