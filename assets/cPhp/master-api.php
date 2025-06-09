<?php
// master-api.php
require_once __DIR__ . '/config/bootstrap.php';
include_once(__DIR__ . '/server-config.php');

// WooCommerce REST API Credentials loaded from environment or settings.json
$settings = json_decode(file_get_contents(__DIR__ . '/../data/settings.json'), true) ?: [];
$consumer_key    = getenv('WC_CONSUMER_KEY')    ?: ($settings['woocommerce_ck'] ?? '');
$consumer_secret = getenv('WC_CONSUMER_SECRET') ?: ($settings['woocommerce_cs'] ?? '');
$store_url       = getenv('STORE_URL')          ?: ($settings['store_url']      ?? '');
?>
