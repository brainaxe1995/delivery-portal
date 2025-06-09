<?php
require_once __DIR__ . '/config/bootstrap.php';
// portal/assets/cPhp/get_invoices.php

header('Content-Type: application/json; charset=utf-8');
$dataFile = __DIR__ . '/../data/invoices.json';
if (!file_exists($dataFile)) {
    echo json_encode([]);
    exit;
}
$invoices = json_decode(file_get_contents($dataFile), true);
foreach($invoices as &$inv){
    if(isset($inv['items']) && is_array($inv['items']) && $inv['items']){
        if(!isset($inv['customer'])){
            $inv['customer'] = $inv['items'][0]['customerName'] ?? '';
        }
        if(!isset($inv['amount'])){
            $total = 0;
            foreach($inv['items'] as $it){
                $total += isset($it['totalCost']) ? (float)$it['totalCost'] : 0;
            }
            $inv['amount'] = $total;
        }
    }
    $inv['status'] = $inv['status'] ?? 'Pending';
    $inv['date'] = $inv['date'] ?? date('Y-m-d');
}
unset($inv);
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
$total = count($invoices);
$total_pages = max(1, (int)ceil($total / $per_page));
header('X-My-TotalPages: ' . $total_pages);
$start = ($page - 1) * $per_page;
$items = array_slice($invoices, $start, $per_page);
echo json_encode($items);
exit;
?>
