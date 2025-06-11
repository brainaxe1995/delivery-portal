<?php
require_once __DIR__ . '/config/bootstrap.php';
// assets/cPhp/webhook_handler.php
// Basic webhook receiver that logs JSON payloads.

$logFile = __DIR__ . '/../data/webhook_logs.json';
$body = file_get_contents('php://input');
if ($body === false) {
    http_response_code(400);
    echo json_encode(['error' => 'No payload']);
    exit;
}

$data = json_decode($body, true);
if ($data === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

$entry = [
    'timestamp' => date('c'),
    'payload'   => $data
];

$fp = fopen($logFile, 'c+');
if ($fp === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to open log file']);
    exit;
}
flock($fp, LOCK_EX);
$contents = stream_get_contents($fp);
$logs = $contents ? json_decode($contents, true) : [];
if (!is_array($logs)) $logs = [];
$logs[] = $entry;
// Keep only last 100 entries
if (count($logs) > 100) $logs = array_slice($logs, -100);
rewind($fp);
file_put_contents($logFile, json_encode($logs, JSON_PRETTY_PRINT));
flock($fp, LOCK_UN);
fclose($fp);

header('Content-Type: application/json');
echo json_encode(['status' => 'ok']);

