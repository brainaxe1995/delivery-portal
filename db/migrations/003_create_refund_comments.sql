CREATE TABLE IF NOT EXISTS refund_comments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    refund_id INTEGER,
    user_id INTEGER,
    comment TEXT,
    timestamp TEXT DEFAULT CURRENT_TIMESTAMP
);
