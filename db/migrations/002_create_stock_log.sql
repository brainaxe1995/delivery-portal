CREATE TABLE stock_log (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id BIGINT NOT NULL,
  change_qty INT NOT NULL,
  reason VARCHAR(255) NOT NULL,
  timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
