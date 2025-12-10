<?php
session_start();

// Simulate admin login
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'admin';

// Load database config
require_once __DIR__ . '/src/Config/database.php';

// Check categories
echo "=== CATEGORIES IN DATABASE ===\n";
$result = $pdo->query("SELECT id, name FROM categories");
foreach ($result as $cat) {
    echo "ID: {$cat['id']}, Name: {$cat['name']}\n";
}

// Test delete logic
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['name'] = 'Beverages';

echo "\n=== TESTING DELETE FOR: Beverages ===\n";

// Check if category has products
$sql = "SELECT COUNT(*) as cnt FROM products WHERE category = ? AND deleted_at IS NULL";
$stmt = $pdo->prepare($sql);
$stmt->execute(['Beverages']);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Products with this category: " . $result['cnt'] . "\n";

if ($result['cnt'] > 0) {
    echo "Cannot delete - category has products\n";
} else {
    echo "Deleting category 'Beverages'...\n";
    $deleteSql = "DELETE FROM categories WHERE name = ?";
    $deleteStmt = $pdo->prepare($deleteSql);
    $success = $deleteStmt->execute(['Beverages']);
    echo "Delete result: " . ($success ? "SUCCESS" : "FAILED") . "\n";
    
    // Verify
    $checkResult = $pdo->query("SELECT COUNT(*) as cnt FROM categories WHERE name = 'Beverages'");
    $checkRow = $checkResult->fetch(PDO::FETCH_ASSOC);
    echo "Category still in DB: " . $checkRow['cnt'] . "\n";
}

echo "\n=== CATEGORIES AFTER TEST ===\n";
$result = $pdo->query("SELECT id, name FROM categories");
foreach ($result as $cat) {
    echo "ID: {$cat['id']}, Name: {$cat['name']}\n";
}
?>
