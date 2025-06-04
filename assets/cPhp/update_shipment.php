<?php
// portal/assets/cPhp/upload_manifest.php
require_once __DIR__ . '/master-api.php';

if (empty($_FILES['manifest']) || $_FILES['manifest']['error']) {
  http_response_code(400);
  echo json_encode(['error'=>'No file']);
  exit;
}

$tmp = fopen($_FILES['manifest']['tmp_name'],'r');
if (!$tmp) {
  http_response_code(500);
  echo json_encode(['error'=>'Upload failed']);
  exit;
}

// Skip header if present
$first = fgetcsv($tmp);

while (($row = fgetcsv($tmp)) !== false) {
  list($order_id,$provider,$tracking,$eta) = $row;

  $payload = ['meta_data'=>[
    ['key'=>'_wot_tracking_number','value'=>$tracking],
    ['key'=>'_wot_tracking_carrier','value'=>$provider],
    ['key'=>'_wot_eta','value'=>$eta]
  ]];

  // PUT to Woo
  $ch = curl_init(rtrim($store_url,'/') . "/wp-json/wc/v3/orders/{$order_id}");
  curl_setopt_array($ch,[
    CURLOPT_CUSTOMREQUEST  => 'PUT',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERPWD        => "$consumer_key:$consumer_secret",
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS     => json_encode($payload)
  ]);
  curl_exec($ch);
  curl_close($ch);
}

echo json_encode(['success'=>true]);
exit;
?>
