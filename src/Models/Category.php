<?php
require_once __DIR__ . '/Database.php';

class Category extends Database {
    
    public function getAll() {
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        return $this->fetchAll($sql);
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM categories WHERE id = ?";
        return $this->fetch($sql, [$id]);
    }
    
    public function create($data) {
        $sql = "INSERT INTO categories (name, description) VALUES (?, ?)";
        $this->query($sql, [
            $data['name'],
            $data['description'] ?? null
        ]);
        return $this->lastInsertId();
    }
    
    public function update($id, $data) {
        $fields = [];
        $params = [];
        
        if (isset($data['name'])) {
            $fields[] = "name = ?";
            $params[] = $data['name'];
        }
        if (isset($data['description'])) {
            $fields[] = "description = ?";
            $params[] = $data['description'];
        }
        
        if (empty($fields)) return;
        
        $params[] = $id;
        $sql = "UPDATE categories SET " . implode(", ", $fields) . " WHERE id = ?";
        $this->query($sql, $params);
    }
    
    public function delete($id) {
        $sql = "DELETE FROM categories WHERE id = ?";
        $this->query($sql, [$id]);
    }
    
    public function exists($name, $excludeId = null) {
        if ($excludeId) {
            $sql = "SELECT COUNT(*) as cnt FROM categories WHERE name = ? AND id != ?";
            $row = $this->fetch($sql, [$name, $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) as cnt FROM categories WHERE name = ?";
            $row = $this->fetch($sql, [$name]);
        }
        return ($row && isset($row['cnt']) && $row['cnt'] > 0);
    }
}
?>
