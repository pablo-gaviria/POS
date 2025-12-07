<?php
require_once __DIR__ . '/../Models/User.php';

class UserController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function index() {
        $users = $this->userModel->getAll();
        include __DIR__ . '/../Views/users/index.php';
    }
    
    public function create() {
        include __DIR__ . '/../Views/users/create.php';
    }
    
    public function store() {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'staff';
        
        if (empty($name) || empty($email) || empty($password)) {
            $_SESSION['error'] = 'All fields are required';
            $this->create();
            return;
        }
        
        // Check if email already exists
        if ($this->userModel->getByEmail($email)) {
            $_SESSION['error'] = 'Email already exists';
            $this->create();
            return;
        }
        
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role
        ];
        
        $this->userModel->create($data);
        $_SESSION['success'] = 'User created successfully';
        header('Location: ?page=users');
        exit;
    }
    
    public function edit($id) {
        $user = $this->userModel->getById($id);
        if (!$user) {
            $_SESSION['error'] = 'User not found';
            header('Location: ?page=users');
            exit;
        }
        include __DIR__ . '/../Views/users/edit.php';
    }
    
    public function update() {
        $id = $_POST['id'] ?? 0;
        $user = $this->userModel->getById($id);
        
        if (!$user) {
            $_SESSION['error'] = 'User not found';
            header('Location: ?page=users');
            exit;
        }
        
        $data = [];
        if (isset($_POST['name'])) $data['name'] = $_POST['name'];
        if (isset($_POST['email'])) $data['email'] = $_POST['email'];
        if (isset($_POST['password']) && !empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }
        if (isset($_POST['role'])) $data['role'] = $_POST['role'];
        if (isset($_POST['status'])) $data['status'] = $_POST['status'];
        
        $this->userModel->update($id, $data);
        $_SESSION['success'] = 'User updated successfully';
        header('Location: ?page=users');
        exit;
    }
    
    public function delete($id) {
        if ($id === $_SESSION['user_id']) {
            $_SESSION['error'] = 'Cannot delete your own account';
            header('Location: ?page=users');
            exit;
        }
        
        $user = $this->userModel->getById($id);
        if (!$user) {
            $_SESSION['error'] = 'User not found';
        } else {
            $this->userModel->delete($id);
            $_SESSION['success'] = 'User deleted successfully';
        }
        
        header('Location: ?page=users');
        exit;
    }
}
?>
