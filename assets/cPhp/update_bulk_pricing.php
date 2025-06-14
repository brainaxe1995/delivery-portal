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
