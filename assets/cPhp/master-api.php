<?php
// master-api.php
include_once(__DIR__ . '/server-config.php');

// WooCommerce REST API Credentials loaded from environment variables or .env
$required = ['WOOCOMMERCE_CK', 'WOOCOMMERCE_CS', 'STORE_URL'];
// If any required variable is missing attempt to load from a .env file
$missing = false;
foreach ($required as $var) {
    if (getenv($var) === false || getenv($var) === '') {
        $missing = true;
        break;
    }
}

if ($missing) {
    $envFile = dirname(__DIR__, 2) . '/.env';
    if (is_readable($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) {
                continue;
            }
            [$name, $value] = array_map('trim', explode('=', $line, 2));
            if ($name !== '' && getenv($name) === false) {
                putenv("{$name}={$value}");
                $_ENV[$name] = $value;
            }
        }
    }
}

$consumer_key    = getenv('WOOCOMMERCE_CK') ?: ($_ENV['WOOCOMMERCE_CK'] ?? '');
$consumer_secret = getenv('WOOCOMMERCE_CS') ?: ($_ENV['WOOCOMMERCE_CS'] ?? '');
$store_url       = getenv('STORE_URL')       ?: ($_ENV['STORE_URL'] ?? '');

if (!$consumer_key || !$consumer_secret || !$store_url) {
    die('Environment variables WOOCOMMERCE_CK, WOOCOMMERCE_CS and STORE_URL must be set.');
}
?>
