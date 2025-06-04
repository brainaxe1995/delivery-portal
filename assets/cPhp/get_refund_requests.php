<?php
// assets/cPhp/get_refund_requests.php
// Return a list of refund requests for the refund dashboard.
// In production this would pull from WooCommerce or a database.
// For this demo we read a local JSON file under uploads/.

header('Content-Type: application/json; charset=utf-8');

$file = __DIR__ . '/../uploads/refund_requests.json';
if (!file_exists($file)) {
    echo json_encode([]);
    exit;
}

$raw = file_get_contents($file);
if ($raw === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to read data']);
    exit;
}

echo $raw;
exit;
?>
