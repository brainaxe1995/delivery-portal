<?php
// Autoload and phpdotenv bootstrap
$autoloadPath = __DIR__ . '/../../../vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
    if (class_exists(\Dotenv\Dotenv::class)) {
        $dotenv = \Dotenv\Dotenv::createImmutable(dirname($autoloadPath));
        $dotenv->safeLoad();
    }
}
// If autoload.php is missing, skip phpdotenv without error
?>
