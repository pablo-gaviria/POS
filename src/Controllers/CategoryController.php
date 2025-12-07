<?php
require_once __DIR__ . '/../Models/Category.php';

class CategoryController {
    private $categoryModel;
    
    public function __construct() {
        $this->categoryModel = new Category();
    }
    
    public function index() {
        $categories = $this->categoryModel->getAll();
        include __DIR__ . '/../Views/categories/index.php';
    }
    
    public function create() {
        include __DIR__ . '/../Views/categories/create.php';
    }
    
    public function store() {
        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];
        
        if (empty($data['name'])) {
            $_SESSION['error'] = 'Category name is required';
        } elseif ($this->categoryModel->exists($data['name'])) {
            $_SESSION['error'] = 'Category already exists';
        } else {
            $this->categoryModel->create($data);
            $_SESSION['success'] = 'Category created successfully';
            header('Location: ?page=categories');
            exit;
        }
        
        $this->create();
    }
    
    public function edit($id) {
        $category = $this->categoryModel->getById($id);
        if (!$category) {
            $_SESSION['error'] = 'Category not found';
            header('Location: ?page=categories');
            exit;
        }
        include __DIR__ . '/../Views/categories/edit.php';
    }
    
    public function update() {
        $id = $_POST['id'] ?? 0;
        $category = $this->categoryModel->getById($id);
        
        if (!$category) {
            $_SESSION['error'] = 'Category not found';
            header('Location: ?page=categories');
            exit;
        }
        
        $data = [
            'name' => $_POST['name'] ?? $category['name'],
            'description' => $_POST['description'] ?? $category['description']
        ];
        
        if (empty($data['name'])) {
            $_SESSION['error'] = 'Category name is required';
            $this->edit($id);
            return;
        } elseif ($this->categoryModel->exists($data['name'], $id)) {
            $_SESSION['error'] = 'Category name already exists';
            $this->edit($id);
            return;
        }
        
        $this->categoryModel->update($id, $data);
        $_SESSION['success'] = 'Category updated successfully';
        header('Location: ?page=categories');
        exit;
    }
    
    public function delete($id = null) {
        // Handle delete by POST (from AJAX)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
            $categoryName = $_POST['name'];
            
            // Check if category is in use
            require_once __DIR__ . '/../Models/Database.php';
            $db = new Database();
            $sql = "SELECT COUNT(*) as cnt FROM products WHERE category = ? AND deleted_at IS NULL";
            $stmt = $db->prepare($sql);
            $stmt->execute([$categoryName]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && $result['cnt'] > 0) {
                $_SESSION['error'] = 'Cannot delete category with products. Reassign products first.';
            } else {
                // Delete by name
                $deleteSql = "DELETE FROM categories WHERE name = ?";
                $deleteStmt = $db->prepare($deleteSql);
                $deleteStmt->execute([$categoryName]);
                $_SESSION['success'] = 'Category deleted successfully';
            }
            
            // Return success/error for AJAX
            echo json_encode(['success' => isset($_SESSION['success'])]);
            return;
        }
        
        // Handle delete by ID (from dashboard)
        if (!$id) {
            header('Location: ?page=categories');
            exit;
        }
        
        $category = $this->categoryModel->getById($id);
        if (!$category) {
            $_SESSION['error'] = 'Category not found';
        } else {
            // Check if category is in use
            require_once __DIR__ . '/../Models/Database.php';
            $db = new Database();
            $sql = "SELECT COUNT(*) as cnt FROM products WHERE category = ? AND deleted_at IS NULL";
            $stmt = $db->prepare($sql);
            $stmt->execute([$category['name']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && $result['cnt'] > 0) {
                $_SESSION['error'] = 'Cannot delete category with products. Reassign products first.';
            } else {
                $this->categoryModel->delete($id);
                $_SESSION['success'] = 'Category deleted successfully';
            }
        }
        header('Location: ?page=categories');
        exit;
    }
}
?>
