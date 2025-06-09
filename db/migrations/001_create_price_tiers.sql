CREATE TABLE price_tiers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id BIGINT NOT NULL,
  min_qty INT NOT NULL,
  max_qty INT NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL
);
