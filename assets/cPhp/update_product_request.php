<?php
require_once __DIR__ . '/config/bootstrap.php';
// assets/cPhp/update_product_request.php
// Update the status of a product request in the SQLite database

require_once __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

// Gather JSON or form payload
$raw     = file_get_contents('php://input');
$payload = json_decode($raw, true) ?: $_POST;

$id     = isset($payload['id']) ? (int)$payload['id'] : 0;
$status = $payload['status'] ?? '';

if (!$id || !in_array($status, ['Approved', 'Rejected'], true)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

// Update the row and report success only if a record was affected
$stmt = $db->prepare('UPDATE product_requests SET status = :status WHERE id = :id');
$stmt->bindValue(':status', $status, SQLITE3_TEXT);
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$result = $stmt->execute();

if ($db->changes() === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Request not found']);
    exit;
}

echo json_encode(['success' => true]);
?>
