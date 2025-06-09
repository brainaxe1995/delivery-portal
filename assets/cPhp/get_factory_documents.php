<?php
require_once __DIR__ . '/config/bootstrap.php';
// portal/assets/cPhp/get_factory_documents.php
require_once __DIR__ . '/db.php';

$res  = $db->query('SELECT * FROM factory_documents ORDER BY uploaded_at DESC');
$rows = [];
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $rows[] = $row;
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($rows);
?>
