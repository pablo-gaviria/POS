<?php
require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Models/Category.php';
require_once __DIR__ . '/../Config/constants.php';

class ProductController {
    private $productModel;
    private $categoryModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }
    
    public function index() {
        $products = $this->productModel->getAll();
        include __DIR__ . '/../Views/products/index.php';
    }
    
    public function create() {
        $categories = $this->categoryModel->getAll();
        $nextSku = $this->generateNextSku();
        include __DIR__ . '/../Views/products/create.php';
    }
    
    public function store() {
        $data = [
            'sku' => isset($_POST['sku']) ? trim($_POST['sku']) : '',
            'barcode' => isset($_POST['barcode']) ? trim($_POST['barcode']) : null,
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'category' => $_POST['category'] ?? '',
            'price' => floatval($_POST['price'] ?? 0),
            'cost' => floatval($_POST['cost'] ?? 0),
            'quantity' => intval($_POST['quantity'] ?? 0),
            'reorder_level' => intval($_POST['reorder_level'] ?? 10)
        ];

        // Auto-generate SKU if not provided
        if (empty($data['sku'])) {
            $data['sku'] = $this->generateNextSku();
        }

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
            $imagePath = $this->handleImageUpload($_FILES['image']);
            if ($imagePath) {
                $data['image'] = $imagePath;
            } elseif (isset($_SESSION['error'])) {
                // Error already set by handleImageUpload
                $categories = $this->categoryModel->getAll();
                include __DIR__ . '/../Views/products/create.php';
                return;
            }
        }

        if (empty($data['sku']) || empty($data['name']) || empty($data['price'])) {
            $_SESSION['error'] = 'SKU, Name, and Price are required';
        } elseif (!empty($data['barcode']) && $this->productModel->existsByBarcode($data['barcode'])) {
            $_SESSION['error'] = 'Barcode already exists. Please use a unique barcode.';
        } else {
            $this->productModel->create($data);
            $_SESSION['success'] = 'Product created successfully';
            header('Location: ?page=products');
            exit;
        }

        $this->create();
    }
    
    public function edit($id) {
        $product = $this->productModel->getById($id);
        $categories = $this->categoryModel->getAll();
        if (!$product) {
            $_SESSION['error'] = 'Product not found';
            header('Location: ?page=products');
            exit;
        }
        include __DIR__ . '/../Views/products/edit.php';
    }
    
    public function update() {
        $id = $_POST['id'] ?? 0;
        $product = $this->productModel->getById($id);
        
        if (!$product) {
            $_SESSION['error'] = 'Product not found';
            header('Location: ?page=products');
            exit;
        }
        
        $data = [
            'sku' => $_POST['sku'] ?? $product['sku'],
            'barcode' => $_POST['barcode'] ?? $product['barcode'],
            'name' => $_POST['name'] ?? $product['name'],
            'description' => $_POST['description'] ?? $product['description'],
            'category' => $_POST['category'] ?? $product['category'],
            'price' => floatval($_POST['price'] ?? $product['price']),
            'cost' => floatval($_POST['cost'] ?? $product['cost']),
            'quantity' => intval($_POST['quantity'] ?? $product['quantity']),
            'reorder_level' => intval($_POST['reorder_level'] ?? $product['reorder_level'])
        ];

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
            $imagePath = $this->handleImageUpload($_FILES['image']);
            if ($imagePath) {
                // Delete old image if exists
                if (!empty($product['image']) && file_exists(__DIR__ . '/../../' . $product['image'])) {
                    unlink(__DIR__ . '/../../' . $product['image']);
                }
                $data['image'] = $imagePath;
            } elseif (isset($_SESSION['error'])) {
                // Error already set by handleImageUpload
                $categories = $this->categoryModel->getAll();
                $this->edit($id);
                return;
            }
        }

        // Handle image deletion checkbox
        if (isset($_POST['delete_image']) && $_POST['delete_image'] === 'on') {
            if (!empty($product['image']) && file_exists(__DIR__ . '/../../' . $product['image'])) {
                unlink(__DIR__ . '/../../' . $product['image']);
            }
            $data['image'] = null;
        }
        
        // Prevent updating to an already used barcode
        if (!empty($data['barcode']) && $this->productModel->existsByBarcode($data['barcode'], $id)) {
            $_SESSION['error'] = 'Barcode already exists for another product.';
            $categories = $this->categoryModel->getAll();
            $this->edit($id);
            return;
        }

        $this->productModel->update($id, $data);
        $_SESSION['success'] = 'Product updated successfully';
        header('Location: ?page=products');
        exit;
    }
    
    public function delete($id) {
        $product = $this->productModel->getById($id);
        if (!$product) {
            $_SESSION['error'] = 'Product not found';
        } else {
            // Delete image if exists
            if (!empty($product['image']) && file_exists(__DIR__ . '/../../' . $product['image'])) {
                unlink(__DIR__ . '/../../' . $product['image']);
            }
            $this->productModel->delete($id);
            $_SESSION['success'] = 'Product deleted successfully';
        }
        header('Location: ?page=products');
        exit;
    }
    
    private function handleImageUpload($file) {
        $maxSize = 5 * 1024 * 1024; // 5MB
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $uploadDir = __DIR__ . '/../../public/assets/uploads/products/';
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Validate file
        if ($file['size'] > $maxSize) {
            $_SESSION['error'] = 'Image file is too large. Maximum 5MB allowed.';
            return false;
        }
        
        if (!in_array($file['type'], $allowedTypes)) {
            $_SESSION['error'] = 'Invalid image type. Allowed: JPG, PNG, GIF, WebP.';
            return false;
        }
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Error uploading file. Please try again.';
            return false;
        }
        
        // Generate unique filename
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('product_') . '.' . $ext;
        $filepath = $uploadDir . $filename;
        
        // Move file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return 'assets/uploads/products/' . $filename;
        }
        
        $_SESSION['error'] = 'Failed to save image file.';
        return false;
    }

    private function generateNextSku() {
        // Get the highest numeric SKU from the database
        $sql = "SELECT sku FROM products WHERE sku REGEXP '^[0-9]+$' ORDER BY CAST(sku AS UNSIGNED) DESC LIMIT 1";
        $result = $this->productModel->fetch($sql);

        if ($result && isset($result['sku'])) {
            $nextSku = intval($result['sku']) + 1;
        } else {
            $nextSku = 123; // Start from 123 as requested
        }

        return (string)$nextSku;
    }
}
?>
