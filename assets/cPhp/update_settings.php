<?php
// portal/assets/cPhp/update_settings.php
header('Content-Type: application/json; charset=utf-8');
$file = __DIR__ . '/../data/settings.json';
$raw = file_get_contents('php://input');
$data = json_decode($raw, true) ?: $_POST;
if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid payload']);
    exit;
}
file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
echo json_encode(['success' => true]);
?>
