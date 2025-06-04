<?php
// master-api.php
include_once(__DIR__ . '/server-config.php');

// WooCommerce REST API Credentials loaded from environment variables
$consumer_key    = getenv('WOOCOMMERCE_CK');
$consumer_secret = getenv('WOOCOMMERCE_CS');
$store_url       = getenv('STORE_URL');

if (!$consumer_key || !$consumer_secret || !$store_url) {
    die('Environment variables WOOCOMMERCE_CK, WOOCOMMERCE_CS and STORE_URL must be set.');
}
?>
