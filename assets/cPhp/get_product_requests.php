<?php
// portal/assets/cPhp/get_product_requests.php
require_once __DIR__ . '/db.php';

$res  = $db->query('SELECT * FROM product_requests ORDER BY requested_at DESC');
$rows = [];
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $rows[] = $row;
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($rows);
?>
