<?php
require_once __DIR__ . '/../Models/Sale.php';
require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Models/Inventory.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Config/constants.php';

class DashboardController {
    private $saleModel;
    private $productModel;
    private $inventoryModel;
    private $userModel;
    
    public function __construct() {
        $this->saleModel = new Sale();
        $this->productModel = new Product();
        $this->inventoryModel = new Inventory();
        $this->userModel = new User();
    }
    
    public function index() {
        // Get today's sales
        $todaysSales = $this->saleModel->getTotalSales(date('Y-m-d'), date('Y-m-d'));
        $todaysCount = $this->saleModel->getSalesCount(date('Y-m-d'), date('Y-m-d'));
        
        // Get inventory status
        $inventoryStatus = $this->inventoryModel->getInventoryStatus();
        
        // Get low stock items
        $lowStockItems = $this->inventoryModel->getLowStockItems();
        
        // Get recent sales
        $recentSales = $this->saleModel->getAll(10, 0);
        
        // Get daily sales data for chart
        $dailySales = $this->saleModel->getDailySales();
        
        // Get total users
        $users = $this->userModel->getAll();
        $userCount = count($users);
        
        // Get this month's sales
        $monthStart = date('Y-m-01');
        $monthEnd = date('Y-m-t');
        $monthSales = $this->saleModel->getTotalSales($monthStart, $monthEnd);
        
        include __DIR__ . '/../Views/dashboard.php';
    }
}
?>
