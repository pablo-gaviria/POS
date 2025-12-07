<?php
require_once __DIR__ . '/../Models/Sale.php';
require_once __DIR__ . '/../Models/SaleItem.php';
require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Models/Inventory.php';
require_once __DIR__ . '/../Config/constants.php';

class SalesController {
    private $saleModel;
    private $saleItemModel;
    private $productModel;
    private $inventoryModel;
    
    public function __construct() {
        $this->saleModel = new Sale();
        $this->saleItemModel = new SaleItem();
        $this->productModel = new Product();
        $this->inventoryModel = new Inventory();
    }
    
    public function index() {
        $sales = $this->saleModel->getAll();
        include __DIR__ . '/../Views/sales/index.php';
    }
    
    public function create() {
        $products = $this->productModel->getAll();
        include __DIR__ . '/../Views/sales/create.php';
    }
    
    public function store() {
        $items = $_POST['items'] ?? [];
        $paymentMethod = $_POST['payment_method'] ?? 'cash';
        
        if (empty($items)) {
            $_SESSION['error'] = 'Please add items to the sale';
            header('Location: ?page=sales&action=create');
            exit;
        }
        
        $totalAmount = 0;
        foreach ($items as $item) {
            $totalAmount += floatval($item['subtotal']);
        }
        
        $saleData = [
            'cashier_id' => $_SESSION['user_id'],
            'total_amount' => $totalAmount,
            'payment_method' => $paymentMethod
        ];
        
        $saleId = $this->saleModel->create($saleData);
        
        // Add sale items and update inventory
        foreach ($items as $item) {
            $productId = intval($item['product_id']);
            $quantity = intval($item['quantity']);
            $price = floatval($item['price']);
            
            $this->saleItemModel->create($saleId, $productId, $quantity, $price);
            
            // Decrease inventory
            $this->inventoryModel->updateStock($productId, -$quantity, 'sale');
        }
        
        $_SESSION['success'] = 'Sale completed successfully';
        header('Location: ?page=sales&action=receipt&id=' . $saleId);
        exit;
    }
    
    public function history() {
        $sales = $this->saleModel->getAll(50, 0);
        include __DIR__ . '/../Views/sales/history.php';
    }
    
    public function receipt($saleId) {
        $sale = $this->saleModel->getById($saleId);
        $items = $this->saleItemModel->getBySaleId($saleId);
        
        if (!$sale) {
            $_SESSION['error'] = 'Sale not found';
            header('Location: ?page=sales');
            exit;
        }
        
        include __DIR__ . '/../Views/sales/receipt.php';
    }
}
?>
