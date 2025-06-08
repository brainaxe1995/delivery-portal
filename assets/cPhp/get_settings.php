<?php
// portal/assets/cPhp/get_settings.php
header('Content-Type: application/json; charset=utf-8');
$file = __DIR__ . '/../data/settings.json';
if (!file_exists($file)) {
    echo json_encode([]);
    exit;
}
readfile($file);
?>
