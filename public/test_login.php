<?php
// Test database connection and users
require_once __DIR__ . '/../src/Config/database.php';

echo "=== POS System Database Test ===\n\n";

// Test PDO connection
try {
    echo "✓ PDO Connection: OK\n";
} catch (Exception $e) {
    echo "✗ PDO Connection: FAILED - " . $e->getMessage() . "\n";
    exit(1);
}

// Check if users table exists and has data
try {
    $stmt = $pdo->query("SELECT id, name, email, password, role, status FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($users)) {
        echo "✗ No users found in database!\n";
        echo "\nYou need to import the database schema first.\n";
        echo "Run: mysql -u root -p pos_system < database/schema.sql\n";
    } else {
        echo "✓ Found " . count($users) . " user(s) in database:\n\n";
        foreach ($users as $user) {
            echo "  ID: {$user['id']}\n";
            echo "  Name: {$user['name']}\n";
            echo "  Email: {$user['email']}\n";
            echo "  Role: {$user['role']}\n";
            echo "  Status: {$user['status']}\n";
            echo "  Password Hash: " . substr($user['password'], 0, 20) . "...\n\n";
        }
    }
} catch (Exception $e) {
    echo "✗ Query Error: " . $e->getMessage() . "\n";
    echo "\nThe users table doesn't exist. Import the schema:\n";
    echo "mysql -u root -p pos_system < database/schema.sql\n";
}

// Test login with known password
echo "\n=== Testing Login Logic ===\n";

try {
    // Get admin user
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute(['admin@pos.com']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo "✗ Admin user not found\n";
    } else {
        echo "✓ Admin user found: " . $user['name'] . "\n";
        
        // Test password verification
        $testPassword = 'password';
        $hashMatch = password_verify($testPassword, $user['password']);
        
        if ($hashMatch) {
            echo "✓ Password verification: SUCCESS\n";
        } else {
            echo "✗ Password verification: FAILED\n";
            echo "  Expected password: 'password'\n";
            echo "  Stored hash: " . $user['password'] . "\n";
        }
    }
} catch (Exception $e) {
    echo "✗ Test Error: " . $e->getMessage() . "\n";
}

echo "\n=== Database Status ===\n";
try {
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "✓ Tables in database: " . implode(', ', $tables) . "\n";
} catch (Exception $e) {
    echo "✗ Cannot list tables: " . $e->getMessage() . "\n";
}

?>
