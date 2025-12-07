<?php
require_once __DIR__ . '/Database.php';

class Inventory extends Database {
    
    public function getInventoryStatus() {
        $sql = "SELECT 
                COUNT(*) as total_products,
                SUM(quantity) as total_quantity,
                SUM(quantity * cost) as total_cost_value,
                SUM(CASE WHEN quantity <= reorder_level THEN 1 ELSE 0 END) as low_stock_count
                FROM products WHERE deleted_at IS NULL";
        return $this->fetch($sql);
    }
    
    public function getLowStockItems() {
        $fields = "id, sku, name, quantity, reorder_level, (reorder_level - quantity) as shortage";
        if ($this->hasColumn('products', 'barcode')) $fields = "id, sku, barcode, name, quantity, reorder_level, (reorder_level - quantity) as shortage";
        $sql = "SELECT $fields FROM products WHERE quantity <= reorder_level AND deleted_at IS NULL ORDER BY shortage DESC";
        return $this->fetchAll($sql);
    }
    
    public function updateStock($productId, $quantity, $reason = 'adjustment') {
        $sql = "UPDATE products SET quantity = quantity + ? WHERE id = ?";
        $this->query($sql, [$quantity, $productId]);
        
        // Log inventory movement
        $this->logMovement($productId, $quantity, $reason);
    }
    
    public function logMovement($productId, $quantity, $reason) {
        $sql = "INSERT INTO inventory_movements (product_id, quantity_change, reason, created_at) 
                VALUES (?, ?, ?, NOW())";
        $this->query($sql, [$productId, $quantity, $reason]);
    }
    
    public function getMovementHistory($productId, $limit = 50) {
        $sql = "SELECT * FROM inventory_movements WHERE product_id = ? ORDER BY created_at DESC LIMIT " . intval($limit);
        return $this->fetchAll($sql, [$productId]);
    }
}
?>
