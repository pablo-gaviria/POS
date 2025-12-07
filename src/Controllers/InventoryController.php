<?php
require_once __DIR__ . '/../Models/Inventory.php';
require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Config/constants.php';

class InventoryController {
    private $inventoryModel;
    private $productModel;
    
    public function __construct() {
        $this->inventoryModel = new Inventory();
        $this->productModel = new Product();
    }
    
    public function index() {
        $products = $this->productModel->getAll();
        $inventoryStatus = $this->inventoryModel->getInventoryStatus();
        include __DIR__ . '/../Views/inventory/index.php';
    }
    
    public function lowStock() {
        $lowStockItems = $this->inventoryModel->getLowStockItems();
        include __DIR__ . '/../Views/inventory/low-stock.php';
    }
    
    public function updateStock() {
        $productId = $_POST['product_id'] ?? 0;
        $quantity = intval($_POST['quantity'] ?? 0);
        $reason = $_POST['reason'] ?? 'adjustment';
        
        $product = $this->productModel->getById($productId);
        if (!$product) {
            $_SESSION['error'] = 'Product not found';
        } else {
            $this->inventoryModel->updateStock($productId, $quantity, $reason);
            $_SESSION['success'] = 'Stock updated successfully';
        }
        
        header('Location: ?page=inventory');
        exit;
    }
}
?>
