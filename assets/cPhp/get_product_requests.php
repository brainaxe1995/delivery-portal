<?php

// portal/assets/cPhp/get_product_requests.php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

$page     = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page = isset($_GET['per_page']) ? max(1, (int)$_GET['per_page']) : 20;

// Determine total rows and pages
$total_rows  = (int)$db->querySingle('SELECT COUNT(*) FROM product_requests');
$total_pages = max(1, (int)ceil($total_rows / $per_page));
header('X-My-TotalPages: ' . $total_pages);

// Fetch paginated results
$offset = ($page - 1) * $per_page;
$stmt   = $db->prepare('SELECT * FROM product_requests ORDER BY requested_at DESC LIMIT :limit OFFSET :offset');
$stmt->bindValue(':limit',  $per_page, SQLITE3_INTEGER);
$stmt->bindValue(':offset', $offset,   SQLITE3_INTEGER);
$res    = $stmt->execute();

$rows = [];
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $rows[] = $row;
}

echo json_encode($rows);
exit;
?>
