<?php
// Import database schema
$host = 'localhost';
$db = 'pos_system';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $db");
    
    // Select database
    $pdo->exec("USE $db");
    
    // Read schema file
    $schema = file_get_contents(__DIR__ . '/database/schema.sql');
    
    // Remove comments and split by statement
    $schema = preg_replace('/--.*$/m', '', $schema);
    $statements = array_filter(array_map('trim', preg_split('/;/', $schema)));
    
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }
    
    echo "✓ Database schema imported successfully!\n";
    echo "✓ Categories table created\n";
    echo "✓ Demo categories and products inserted\n";
    
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
