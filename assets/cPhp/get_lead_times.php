<?php
require_once __DIR__ . '/config/bootstrap.php';
// portal/assets/cPhp/get_lead_times.php
require_once __DIR__ . '/db.php';

$res  = $db->query('SELECT * FROM lead_times ORDER BY last_updated DESC');
$rows = [];
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $rows[] = $row;
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($rows);
?>
