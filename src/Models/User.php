<?php
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/../Config/constants.php';

class User extends Database {
    
    public function getAll() {
        $sql = "SELECT id, name, email, role, status, created_at FROM users ORDER BY created_at DESC";
        return $this->fetchAll($sql);
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        return $this->fetch($sql, [$id]);
    }
    
    public function getByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        return $this->fetch($sql, [$email]);
    }
    
    public function create($data) {
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (name, email, password, role, status, created_at) 
                VALUES (?, ?, ?, ?, 'active', NOW())";
        $this->query($sql, [
            $data['name'],
            $data['email'],
            $hashedPassword,
            $data['role']
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
        if (isset($data['email'])) {
            $fields[] = "email = ?";
            $params[] = $data['email'];
        }
        if (isset($data['role'])) {
            $fields[] = "role = ?";
            $params[] = $data['role'];
        }
        if (isset($data['password'])) {
            $fields[] = "password = ?";
            $params[] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        if (isset($data['status'])) {
            $fields[] = "status = ?";
            $params[] = $data['status'];
        }
        
        $params[] = $id;
        
        $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE id = ?";
        $this->query($sql, $params);
    }
    
    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        $this->query($sql, [$id]);
    }
    
    public function authenticate($email, $password) {
        $user = $this->getByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
?>
