<?php
// Fix password hashes for demo users
require_once __DIR__ . '/../src/Config/database.php';

echo "=== Updating Demo User Passwords ===\n\n";

$password = 'password';
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

echo "New password hash: $hashedPassword\n\n";

try {
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email IN ('admin@pos.com', 'staff@pos.com', 'cashier@pos.com')");
    $stmt->execute([$hashedPassword]);
    
    echo "✓ Updated " . $stmt->rowCount() . " users with new password hash\n\n";
    
    // Verify the update
    $stmt = $pdo->query("SELECT id, name, email, role FROM users WHERE email IN ('admin@pos.com', 'staff@pos.com', 'cashier@pos.com')");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Updated users:\n";
    foreach ($users as $user) {
        echo "  - {$user['email']} ({$user['name']}) - Role: {$user['role']}\n";
    }
    
    echo "\n✓ Success! You can now login with:\n";
    echo "  Email: admin@pos.com\n";
    echo "  Password: password\n\n";
    echo "  Or use staff@pos.com / cashier@pos.com with the same password\n";
    
} catch (Exception $e) {
    echo "✗ Error updating passwords: " . $e->getMessage() . "\n";
}

?>
