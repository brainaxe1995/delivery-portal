<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');
$settings = [];
$res = @$db->query('SELECT key,value FROM settings');
if($res){
  while($row = $res->fetchArray(SQLITE3_ASSOC)){
    $settings[$row['key']] = $row['value'];
  }
}
if(!$settings){
  $file = __DIR__ . '/../data/settings.json';
  if(is_readable($file)){
    $settings = json_decode(file_get_contents($file), true) ?: [];
  }
}
echo json_encode($settings);
exit;
?>
