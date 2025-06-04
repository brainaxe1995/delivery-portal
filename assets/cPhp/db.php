<?php
// portal/assets/cPhp/db.php

// Path to SQLite database file
$dbFile = __DIR__ . '/../../data.sqlite';

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
?>
