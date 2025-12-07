<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load configuration
require_once __DIR__ . '/../src/Config/database.php';

// Simple router
$page = $_GET['page'] ?? 'login';
$action = $_GET['action'] ?? 'index';

// Check authentication
$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $_SESSION['role'] ?? null;

// If not logged in, redirect to login
if ($page !== 'login' && !$isLoggedIn) {
    header('Location: ?page=login');
    exit;
}

// Route handling
switch($page) {
    case 'login':
        require_once __DIR__ . '/../src/Controllers/AuthController.php';
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->showLoginForm();
        }
        break;
        
    case 'logout':
        session_destroy();
        header('Location: ?page=login');
        exit;
        
    case 'dashboard':
        if (!$isLoggedIn) {
            header('Location: ?page=login');
            exit;
        }
        require_once __DIR__ . '/../src/Controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
        break;
        
    case 'products':
        if (!$isLoggedIn) {
            header('Location: ?page=login');
            exit;
        }
        require_once __DIR__ . '/../src/Controllers/ProductController.php';
        $controller = new ProductController();
        
        switch($action) {
            case 'create':
                if ($userRole === 'admin') {
                    $controller->create();
                }
                break;
            case 'store':
                if ($userRole === 'admin' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->store();
                }
                break;
            case 'edit':
                if ($userRole === 'admin' && isset($_GET['id'])) {
                    $controller->edit($_GET['id']);
                }
                break;
            case 'update':
                if ($userRole === 'admin' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->update();
                }
                break;
            case 'delete':
                if ($userRole === 'admin' && isset($_GET['id'])) {
                    $controller->delete($_GET['id']);
                }
                break;
            default:
                $controller->index();
        }
        break;
        
    case 'inventory':
        if (!$isLoggedIn) {
            header('Location: ?page=login');
            exit;
        }
        require_once __DIR__ . '/../src/Controllers/InventoryController.php';
        $controller = new InventoryController();
        
        switch($action) {
            case 'low-stock':
                $controller->lowStock();
                break;
            case 'update-stock':
                if ($userRole === 'admin' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->updateStock();
                }
                break;
            default:
                $controller->index();
        }
        break;
        
    case 'sales':
        if (!$isLoggedIn) {
            header('Location: ?page=login');
            exit;
        }
        require_once __DIR__ . '/../src/Controllers/SalesController.php';
        $controller = new SalesController();
        
        switch($action) {
            case 'create':
                $controller->create();
                break;
            case 'store':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->store();
                }
                break;
            case 'history':
                $controller->history();
                break;
            case 'receipt':
                if (isset($_GET['id'])) {
                    $controller->receipt($_GET['id']);
                }
                break;
            default:
                $controller->index();
        }
        break;
        
    case 'cashier':
        if (!$isLoggedIn || ($userRole !== 'admin' && $userRole !== 'cashier')) {
            header('Location: ?page=dashboard');
            exit;
        }
        require_once __DIR__ . '/../src/Controllers/CashierController.php';
        $controller = new CashierController();
        
        switch($action) {
            case 'end-shift':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->endShift();
                }
                break;
            default:
                $controller->index();
        }
        break;
        
    case 'categories':
        if ($userRole !== 'admin') {
            header('Location: ?page=dashboard');
            exit;
        }
        require_once __DIR__ . '/../src/Controllers/CategoryController.php';
        $controller = new CategoryController();

        switch($action) {
            case 'create':
                $controller->create();
                break;
            case 'store':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->store();
                }
                break;
            case 'edit':
                if (isset($_GET['id'])) {
                    $controller->edit($_GET['id']);
                }
                break;
            case 'update':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->update();
                }
                break;
            case 'delete':
                if (isset($_GET['id'])) {
                    $controller->delete($_GET['id']);
                }
                break;
            default:
                $controller->index();
        }
        break;

    case 'users':
        if ($userRole !== 'admin') {
            header('Location: ?page=dashboard');
            exit;
        }
        require_once __DIR__ . '/../src/Controllers/UserController.php';
        $controller = new UserController();

        switch($action) {
            case 'create':
                $controller->create();
                break;
            case 'store':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->store();
                }
                break;
            case 'edit':
                if (isset($_GET['id'])) {
                    $controller->edit($_GET['id']);
                }
                break;
            case 'update':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->update();
                }
                break;
            case 'delete':
                if (isset($_GET['id'])) {
                    $controller->delete($_GET['id']);
                }
                break;
            default:
                $controller->index();
        }
        break;

    default:
        if ($isLoggedIn) {
            header('Location: ?page=dashboard');
        } else {
            header('Location: ?page=login');
        }
        exit;
}
?>
