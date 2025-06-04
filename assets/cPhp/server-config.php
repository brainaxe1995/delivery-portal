<?php
$environment = 'local'; // 'local' or 'live'

$config = [
    'local' => ['base_url' => 'http://localhost/portal'],
    'live'  => ['base_url' => 'https://portal.tootfunyachts.com'],
];

// Allow overriding the base URL using an environment variable
$envBaseUrl = getenv('PROJECT_BASE_URL');

$baseUrl = ($envBaseUrl !== false && $envBaseUrl !== '')
    ? $envBaseUrl
    : $config[$environment]['base_url'];

define('PROJECT_BASE_URL', $baseUrl);
?>
