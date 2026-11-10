-- Apply this migration only to an existing database created from an earlier schema.sql.
USE crivit_iran;
ALTER TABLE admins MODIFY role ENUM('owner','manager','warehouse') NOT NULL DEFAULT 'owner';
CREATE TABLE IF NOT EXISTS inventory_movements (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  product_id BIGINT UNSIGNED NOT NULL,
  admin_id BIGINT UNSIGNED NOT NULL,
  movement_type ENUM('in','out','adjustment') NOT NULL,
  quantity INT UNSIGNED NOT NULL,
  note VARCHAR(500) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_inventory_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  CONSTRAINT fk_inventory_admin FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE RESTRICT,
  INDEX idx_inventory_product_date (product_id, created_at)
) ENGINE=InnoDB;
