<?php
require_once __DIR__ . '/Database.php';

class SaleItem extends Database {
    
    public function create($saleId, $productId, $quantity, $price) {
        $sql = "INSERT INTO sale_items (sale_id, product_id, quantity, unit_price, subtotal, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        $subtotal = $quantity * $price;
        $this->query($sql, [$saleId, $productId, $quantity, $price, $subtotal]);
    }
    
    public function getBySaleId($saleId) {
        // Build select fields depending on whether barcode exists
        $fields = "p.name, p.sku";
        if ($this->hasColumn('products', 'barcode')) $fields .= ", p.barcode";
        $sql = "SELECT si.*, $fields FROM sale_items si 
            JOIN products p ON si.product_id = p.id 
            WHERE si.sale_id = ?";
        return $this->fetchAll($sql, [$saleId]);
    }
    
    public function getTopSellingProducts($limit = 10) {
        $select = "p.id, p.name, p.sku";
        $groupBy = "p.id, p.name, p.sku";
        if ($this->hasColumn('products', 'barcode')) {
            $select .= ", p.barcode";
            $groupBy .= ", p.barcode";
        }
        $sql = "SELECT $select, SUM(si.quantity) as total_sold, SUM(si.subtotal) as total_revenue 
            FROM sale_items si 
            JOIN products p ON si.product_id = p.id 
            GROUP BY $groupBy 
            ORDER BY total_sold DESC 
            LIMIT " . intval($limit);
        return $this->fetchAll($sql);
    }
}
?>
