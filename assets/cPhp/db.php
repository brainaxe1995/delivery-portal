<?php
require_once __DIR__ . '/config/bootstrap.php';
// portal/assets/cPhp/db.php

// Path to SQLite database file - allow override via DB_FILE env var
$dbFile = getenv('DB_FILE') ?: (__DIR__ . '/../../data.sqlite');

// Open connection
$db = new SQLite3($dbFile);

// Create tables if they don't exist
$db->exec('CREATE TABLE IF NOT EXISTS product_requests (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    supplier TEXT,
    product TEXT,
    description TEXT,
    requested_at TEXT DEFAULT CURRENT_TIMESTAMP,
    status TEXT,
    notes TEXT
)');

$db->exec('CREATE TABLE IF NOT EXISTS supplier_prices (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    supplier TEXT,
    product TEXT,
    price REAL,
    bulk_price REAL,
    effective_date TEXT
)');

$db->exec('CREATE TABLE IF NOT EXISTS lead_times (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product TEXT,
    supplier TEXT,
    lead_time INTEGER,
    last_updated TEXT DEFAULT CURRENT_TIMESTAMP
)');

$db->exec('CREATE TABLE IF NOT EXISTS factory_documents (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    supplier TEXT,
    product TEXT,
    file_path TEXT,
    uploaded_at TEXT DEFAULT CURRENT_TIMESTAMP
)');

$db->exec('CREATE TABLE IF NOT EXISTS price_tiers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER,
    min_qty INTEGER,
    max_qty INTEGER,
    unit_price REAL
)');

$db->exec('CREATE TABLE IF NOT EXISTS inventory_settings (
    product_id INTEGER PRIMARY KEY,
    safety_stock INTEGER,
    reorder_threshold INTEGER
)');

$db->exec('CREATE TABLE IF NOT EXISTS stock_log (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER,
    change_qty INTEGER,
    reason TEXT,
    timestamp TEXT DEFAULT CURRENT_TIMESTAMP
)');

$db->exec('CREATE TABLE IF NOT EXISTS refund_comments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    refund_id INTEGER,
    user_id INTEGER,
    comment TEXT,
    timestamp TEXT DEFAULT CURRENT_TIMESTAMP
)');
$db->exec("CREATE TABLE IF NOT EXISTS settings (key TEXT PRIMARY KEY,value TEXT)");
?>
