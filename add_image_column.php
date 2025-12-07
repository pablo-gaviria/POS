<?php
// Add missing columns to products table
$host = 'localhost';
$db = 'pos_system';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if barcode column exists
    $checkBarcodeSql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'products' AND COLUMN_NAME = 'barcode'";
    $barcodeResult = $pdo->query($checkBarcodeSql)->fetch(PDO::FETCH_ASSOC);
    
    if (!$barcodeResult) {
        // Add barcode column after sku
        $sql = "ALTER TABLE products ADD COLUMN barcode INT UNIQUE DEFAULT NULL AFTER sku";
        $pdo->exec($sql);
        echo "✓ Barcode column added to products table\n";
    } else {
        echo "✓ Barcode column already exists\n";
    }
    
    // Check if image column exists
    $checkImageSql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'products' AND COLUMN_NAME = 'image'";
    $imageResult = $pdo->query($checkImageSql)->fetch(PDO::FETCH_ASSOC);
    
    if (!$imageResult) {
        // Add image column after barcode
        $sql = "ALTER TABLE products ADD COLUMN image VARCHAR(255) AFTER barcode";
        $pdo->exec($sql);
        echo "✓ Image column added to products table\n";
    } else {
        echo "✓ Image column already exists\n";
    }
    
    echo "✓ All done!\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
