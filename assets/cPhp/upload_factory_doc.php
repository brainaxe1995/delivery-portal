<?php
require_once __DIR__ . '/config/bootstrap.php';
// portal/assets/cPhp/upload_factory_doc.php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_FILES['document'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing document file']);
    exit;
}

$uploadDir = __DIR__ . '/../../uploads';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$fname = basename($_FILES['document']['name']);
$target = $uploadDir . '/' . time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '', $fname);

if (!move_uploaded_file($_FILES['document']['tmp_name'], $target)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to move uploaded file']);
    exit;
}

$supplier = trim($_POST['supplier'] ?? '');
$product  = trim($_POST['product'] ?? '');

$stmt = $db->prepare('INSERT INTO factory_documents (supplier,product,file_path) VALUES (:s,:p,:f)');
$stmt->bindValue(':s', $supplier);
$stmt->bindValue(':p', $product);
$stmt->bindValue(':f', basename($target));
$stmt->execute();

echo json_encode(['status' => 'ok']);
?>
