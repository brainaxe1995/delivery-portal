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

// assets/cPhp/get_product_requests.php
header('Content-Type: application/json; charset=utf-8');
$file = __DIR__ . '/../data/product_requests.json';
if (!file_exists($file)) {
    echo json_encode([]);
    exit;
}
$requests = json_decode(file_get_contents($file), true);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
$total = count($requests);
$total_pages = max(1, (int)ceil($total / $per_page));
header('X-My-TotalPages: ' . $total_pages);
$start = ($page - 1) * $per_page;
$items = array_slice($requests, $start, $per_page);
echo json_encode($items);
exit;
?>
