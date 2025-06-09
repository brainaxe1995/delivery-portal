<?php
require_once __DIR__ . '/config/bootstrap.php';
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

// ------------------------------------------------------------------
// Also update the .env file with credentials from settings
$envPath  = __DIR__ . '/../../.env';
$envLines = file_exists($envPath) ? file($envPath, FILE_IGNORE_NEW_LINES) : [];
$envMap   = [];
foreach ($envLines as $line) {
    if (strpos($line, '=') !== false) {
        [$k, $v] = explode('=', $line, 2);
        $envMap[$k] = $v;
    }
}

$envMap['WC_CONSUMER_KEY']    = $data['woocommerce_ck'] ?? '';
$envMap['WC_CONSUMER_SECRET'] = $data['woocommerce_cs'] ?? '';
$envMap['STORE_URL']          = $data['store_url'] ?? '';

$newEnv = '';
foreach ($envMap as $k => $v) {
    $newEnv .= $k . '=' . $v . PHP_EOL;
}
file_put_contents($envPath, $newEnv);

echo json_encode(['success' => true]);
?>
