<?php
$environment = 'local'; // 'local' or 'live'

$config = [
  'local' => ['base_url' => 'http://localhost/portal'],
  'live'  => ['base_url' => 'https://portal.tootfunyachts.com'],
];

define('PROJECT_BASE_URL', $config[$environment]['base_url']);
?>
