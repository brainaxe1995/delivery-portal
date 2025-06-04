<?php
// portal/assets/cPhp/get_payment_terms.php
header('Content-Type: application/json; charset=utf-8');
$file = __DIR__ . '/../data/payment_terms.json';
if (!file_exists($file)) {
    echo json_encode([]);
    exit;
}
$data = json_decode(file_get_contents($file), true);
echo json_encode($data);
exit;
?>
