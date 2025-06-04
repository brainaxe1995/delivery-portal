<?php
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
