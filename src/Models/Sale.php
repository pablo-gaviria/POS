<?php
require_once __DIR__ . '/Database.php';

class Sale extends Database {
    
    public function getAll($limit = 50, $offset = 0) {
        $sql = "SELECT s.*, u.name as cashier_name FROM sales s 
                LEFT JOIN users u ON s.cashier_id = u.id 
                ORDER BY s.created_at DESC LIMIT " . intval($limit) . " OFFSET " . intval($offset);
        return $this->fetchAll($sql);
    }
    
    public function getById($id) {
        $sql = "SELECT s.*, u.name as cashier_name FROM sales s 
                LEFT JOIN users u ON s.cashier_id = u.id 
                WHERE s.id = ?";
        return $this->fetch($sql, [$id]);
    }
    
    public function create($data) {
        $sql = "INSERT INTO sales (cashier_id, total_amount, payment_method, status, created_at) 
                VALUES (?, ?, ?, 'completed', NOW())";
        $this->query($sql, [
            $data['cashier_id'],
            $data['total_amount'],
            $data['payment_method']
        ]);
        return $this->lastInsertId();
    }
    
    public function getTotalSales($startDate = null, $endDate = null) {
        $sql = "SELECT SUM(total_amount) as total FROM sales WHERE status = 'completed'";
        $params = [];
        
        if ($startDate) {
            $sql .= " AND DATE(created_at) >= ?";
            $params[] = $startDate;
        }
        if ($endDate) {
            $sql .= " AND DATE(created_at) <= ?";
            $params[] = $endDate;
        }
        
        $result = $this->fetch($sql, $params);
        return $result['total'] ?? 0;
    }
    
    public function getSalesCount($startDate = null, $endDate = null) {
        $sql = "SELECT COUNT(*) as count FROM sales WHERE status = 'completed'";
        $params = [];
        
        if ($startDate) {
            $sql .= " AND DATE(created_at) >= ?";
            $params[] = $startDate;
        }
        if ($endDate) {
            $sql .= " AND DATE(created_at) <= ?";
            $params[] = $endDate;
        }
        
        $result = $this->fetch($sql, $params);
        return $result['count'] ?? 0;
    }
    
    public function getDailySales() {
        $sql = "SELECT DATE(created_at) as date, SUM(total_amount) as total, COUNT(*) as count 
                FROM sales WHERE status = 'completed' 
                GROUP BY DATE(created_at) 
                ORDER BY date DESC 
                LIMIT 30";
        return $this->fetchAll($sql);
    }
}
?>
