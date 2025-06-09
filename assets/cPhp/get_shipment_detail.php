<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/master-api.php';
header('Content-Type: application/json; charset=utf-8');
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if(!$id){
  http_response_code(400);
  echo json_encode(['error'=>'Missing id']);
  exit;
}
$ch = curl_init(rtrim($store_url,'/')."/wp-json/wc/v3/orders/{$id}");
curl_setopt_array($ch,[
  CURLOPT_RETURNTRANSFER=>true,
  CURLOPT_USERPWD=>"$consumer_key:$consumer_secret",
  CURLOPT_HTTPHEADER=>['Content-Type: application/json']
]);
$resp = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
http_response_code($code);
if($resp===false){
  echo json_encode(['error'=>'Request failed']);
} else {
  echo $resp;
}
exit;
?>
