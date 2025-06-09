<?php
require_once __DIR__ . '/config/bootstrap.php';
// assets/cPhp/add_order_comment.php
require_once(__DIR__ . '/master-api.php'); // loads $store_url, $consumer_key, $consumer_secret

// 1) Validate inputs
$orderId = $_POST['order_id'] ?? null;
$comment = trim($_POST['comment'] ?? '');
if (!$orderId || !$comment) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing order_id or comment']);
  exit;
}

// 2) Handle file upload (optional)
$fileUrl = '';
if (!empty($_FILES['file']['tmp_name'])) {
  $uploaddir = __DIR__ . '/../uploads/';
  if (!is_dir($uploaddir)) mkdir($uploaddir, 0755, true);

  $origName = $_FILES['file']['name'];
  $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
  $allowed = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
  if (!in_array($ext, $allowed)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid file type']);
    exit;
  }

  $uniqueName = uniqid('', true) . '.' . $ext;
  $dest = $uploaddir . $uniqueName;

  if (move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
    $fileUrl = PROJECT_BASE_URL . '/uploads/' . $uniqueName;
  }
}

// 3) Build order note content
$note = strip_tags($comment);
if ($fileUrl) {
  $note .= "\n\nAttachment: {$fileUrl}";
}

// 4) Post to WooCommerce orders/{id}/notes
$endpoint = "/wp-json/wc/v3/orders/{$orderId}/notes";
$payload  = json_encode(['note' => $note, 'customer_note' => false]); 
$ch = curl_init(rtrim($store_url, '/') . $endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$consumer_key:$consumer_secret");
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
$resp = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($status >= 200 && $status < 300) {
  echo json_encode(['success' => true]);
} else {
  http_response_code($status);
  echo json_encode(['error' => 'WooCommerce API error', 'details' => $resp]);
}
