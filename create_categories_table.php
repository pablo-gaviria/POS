<?php
// Create categories table and insert demo data
$host = 'localhost';
$db = 'pos_system';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create categories table
    $sql = "CREATE TABLE IF NOT EXISTS categories (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(255) UNIQUE NOT NULL,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_name (name)
    )";
    
    $pdo->exec($sql);
    echo "✓ Categories table created successfully\n";
    
    // Insert demo categories
    $categories = [
        ['Beverages', 'Drinks and beverages'],
        ['Snacks', 'Snack items and chips'],
        ['Dairy', 'Dairy products and milk'],
        ['Bakery', 'Bread and bakery items'],
        ['Household', 'Household items and supplies']
    ];
    
    $insertSql = "INSERT IGNORE INTO categories (name, description) VALUES (?, ?)";
    $stmt = $pdo->prepare($insertSql);
    
    foreach ($categories as $cat) {
        $stmt->execute($cat);
    }
    
    echo "✓ Demo categories inserted successfully\n";
    
    // Update existing products to use proper category names
    $updates = [
        "UPDATE products SET category = 'Beverages' WHERE category IN ('beverages', 'beverage')",
        "UPDATE products SET category = 'Snacks' WHERE category IN ('snacks', 'snack')",
        "UPDATE products SET category = 'Dairy' WHERE category IN ('dairy')",
        "UPDATE products SET category = 'Bakery' WHERE category IN ('bakery')",
        "UPDATE products SET category = 'Household' WHERE category IN ('household')"
    ];
    
    foreach ($updates as $sql) {
        $pdo->exec($sql);
    }
    
    echo "✓ Product categories updated\n";
    echo "\n✓ All done! Categories are ready to use.\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
