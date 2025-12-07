-- Migration: Add barcode column and populate it safely
-- 1) Add nullable barcode column
ALTER TABLE products ADD COLUMN barcode INT NULL;

-- 2) If an existing sku column contains only digits, copy numeric sku values into barcode
--    This preserves numeric SKUs (if any) as barcodes.
UPDATE products SET barcode = CAST(sku AS UNSIGNED) WHERE sku REGEXP '^[0-9]+$' AND (barcode IS NULL OR barcode = 0);

-- 3) For remaining rows without barcode, generate barcodes based on id to ensure uniqueness
UPDATE products SET barcode = id + 100000 WHERE barcode IS NULL OR barcode = 0;

-- 4) Make barcode NOT NULL and add unique index
ALTER TABLE products MODIFY COLUMN barcode INT NOT NULL;
ALTER TABLE products ADD UNIQUE INDEX idx_barcode (barcode);

-- 5) Optional: keep the old sku column for reference, or drop it when you are ready.
-- To drop sku column (only after verifying everything works):
-- ALTER TABLE products DROP COLUMN sku;

-- NOTE:
-- Run these commands in a safe environment and BACKUP your database before executing.
-- Adjust the CAST step if your `sku` contains leading zeros you want to preserve.
