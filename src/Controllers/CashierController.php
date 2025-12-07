<?php
class CashierController {
    
    public function index() {
        include __DIR__ . '/../Views/cashier/index.php';
    }
    
    public function endShift() {
        // Logic for ending shift
        $_SESSION['success'] = 'Shift ended successfully';
        header('Location: ?page=dashboard');
        exit;
    }
}
?>
