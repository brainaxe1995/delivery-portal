CREATE TABLE IF NOT EXISTS inventory_settings (
    product_id INTEGER PRIMARY KEY,
    safety_stock INTEGER,
    reorder_threshold INTEGER
);

CREATE TABLE IF NOT EXISTS stock_log (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER,
    change_qty INTEGER,
    reason TEXT,
    timestamp TEXT DEFAULT CURRENT_TIMESTAMP
);
