<?php
require_once __DIR__ . '/Database.php';

class Product extends Database {
    
    public function getAll() {
        $sql = "SELECT * FROM products WHERE deleted_at IS NULL ORDER BY name ASC";
        return $this->fetchAll($sql);
    }

    public function existsByBarcode($barcode, $excludeId = null) {
        // If the barcode column doesn't exist in the DB, treat as no conflict
        if (!$this->hasColumn('products', 'barcode')) return false;

        if ($excludeId) {
            $sql = "SELECT COUNT(*) as cnt FROM products WHERE barcode = ? AND id != ? AND deleted_at IS NULL";
            $row = $this->fetch($sql, [$barcode, $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) as cnt FROM products WHERE barcode = ? AND deleted_at IS NULL";
            $row = $this->fetch($sql, [$barcode]);
        }
        return ($row && isset($row['cnt']) && $row['cnt'] > 0);
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM products WHERE id = ? AND deleted_at IS NULL";
        return $this->fetch($sql, [$id]);
    }
    
    public function create($data) {
        // Build insert dynamically to support databases without barcode column
        $fields = ['sku', 'name', 'description', 'category', 'price', 'cost', 'quantity', 'reorder_level'];
        $placeholders = ['?', '?', '?', '?', '?', '?', '?', '?'];
        $params = [
            $data['sku'] ?? null,
            $data['name'],
            $data['description'] ?? null,
            $data['category'],
            $data['price'],
            $data['cost'],
            $data['quantity'],
            $data['reorder_level']
        ];

        // Add image if provided
        if (isset($data['image'])) {
            array_push($fields, 'image');
            array_push($placeholders, '?');
            array_push($params, $data['image']);
        }

        if (isset($data['barcode']) && !empty($data['barcode']) && $this->hasColumn('products', 'barcode')) {
            array_splice($fields, 1, 0, 'barcode'); // insert barcode after sku
            array_splice($placeholders, 1, 0, '?');
            array_splice($params, 1, 0, [$data['barcode']]);
        }

        $sql = "INSERT INTO products (" . implode(', ', $fields) . ", status, created_at) VALUES (" . implode(', ', $placeholders) . ", 'active', NOW())";
        $this->query($sql, $params);
        return $this->lastInsertId();
    }
    
    public function update($id, $data) {
        $fields = ['updated_at' => date('Y-m-d H:i:s')];
        if (isset($data['sku'])) $fields['sku'] = $data['sku'];
        if (isset($data['barcode']) && $this->hasColumn('products', 'barcode')) $fields['barcode'] = $data['barcode'];
        if (isset($data['name'])) $fields['name'] = $data['name'];
        if (isset($data['description'])) $fields['description'] = $data['description'];
        if (isset($data['category'])) $fields['category'] = $data['category'];
        if (isset($data['price'])) $fields['price'] = $data['price'];
        if (isset($data['cost'])) $fields['cost'] = $data['cost'];
        if (isset($data['quantity'])) $fields['quantity'] = $data['quantity'];
        if (isset($data['reorder_level'])) $fields['reorder_level'] = $data['reorder_level'];
        if (isset($data['status'])) $fields['status'] = $data['status'];
        if (isset($data['image'])) $fields['image'] = $data['image'];
        
        $fieldStrings = [];
        $params = [];
        foreach ($fields as $key => $value) {
            $fieldStrings[] = "$key = ?";
            $params[] = $value;
        }
        $params[] = $id;
        
        $sql = "UPDATE products SET " . implode(", ", $fieldStrings) . " WHERE id = ?";
        $this->query($sql, $params);
    }
    
    public function delete($id) {
        $sql = "UPDATE products SET deleted_at = NOW() WHERE id = ?";
        $this->query($sql, [$id]);
    }
    
    public function getLowStock() {
        $sql = "SELECT * FROM products WHERE quantity <= reorder_level AND deleted_at IS NULL ORDER BY quantity ASC";
        return $this->fetchAll($sql);
    }
    
    public function getByCategory($category) {
        $sql = "SELECT * FROM products WHERE category = ? AND deleted_at IS NULL ORDER BY name ASC";
        return $this->fetchAll($sql, [$category]);
    }
    
    public function search($term) {
        $searchTerm = "%$term%";
        // Build query dynamically depending on whether the barcode column exists
        if ($this->hasColumn('products', 'barcode')) {
            $sql = "SELECT * FROM products WHERE (name LIKE ? OR sku LIKE ? OR barcode LIKE ? OR description LIKE ?) AND deleted_at IS NULL";
            return $this->fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        } else {
            $sql = "SELECT * FROM products WHERE (name LIKE ? OR sku LIKE ? OR description LIKE ?) AND deleted_at IS NULL";
            return $this->fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm]);
        }
    }
}
?>
