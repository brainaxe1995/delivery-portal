<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');
$file = __DIR__ . '/../data/settings.json';
$raw  = file_get_contents('php://input');
$data = json_decode($raw, true) ?: $_POST;
if(!is_array($data)){
  http_response_code(400);
  echo json_encode(['error'=>'Invalid payload']);
  exit;
}
$db->exec('BEGIN');
try{
  file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
  $stmt = $db->prepare('REPLACE INTO settings(key,value) VALUES(:k,:v)');
  foreach($data as $k=>$v){
    $stmt->bindValue(':k',$k,SQLITE3_TEXT);
    $stmt->bindValue(':v',$v,SQLITE3_TEXT);
    $stmt->execute();
  }
  $db->exec('COMMIT');
}catch(Exception $e){
  $db->exec('ROLLBACK');
  http_response_code(500);
  echo json_encode(['error'=>$e->getMessage()]);
  exit;
}
$envPath = __DIR__ . '/../../.env';
$dotenvData = file_exists($envPath)? file($envPath, FILE_IGNORE_NEW_LINES):[];
$updates=[
  'WC_CONSUMER_KEY'=>$data['woocommerce_ck']??'',
  'WC_CONSUMER_SECRET'=>$data['woocommerce_cs']??'',
  'STORE_URL'=>$data['store_url']??''
];
foreach($updates as $key=>$val){
  $found=false;
  foreach($dotenvData as &$line){
    if(strpos($line,"{$key}=")===0){
      $line="{$key}=\"{$val}\"";
      $found=true;
      break;
    }
  }
  if(!$found){
    $dotenvData[]="{$key}=\"{$val}\"";
  }
}
file_put_contents($envPath, implode(PHP_EOL,$dotenvData));
echo json_encode(['success'=>true]);
?>
