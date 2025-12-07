<?php
require_once __DIR__ . '/../Models/User.php';

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function showLoginForm() {
        include __DIR__ . '/../Views/auth/login.php';
    }
    
    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $error = '';
        
        if (empty($email) || empty($password)) {
            $error = 'Email and password are required';
        } else {
            $user = $this->userModel->authenticate($email, $password);
            
            if ($user) {
                if ($user['status'] !== 'active') {
                    $error = 'Your account has been deactivated';
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['email'] = $user['email'];
                    
                    header('Location: ?page=dashboard');
                    exit;
                }
            } else {
                $error = 'Invalid email or password';
            }
        }
        
        include __DIR__ . '/../Views/auth/login.php';
    }
}
?>
