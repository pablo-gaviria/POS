<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">
                <h1>TASTY MIX</h1>
                <span class="role-badge"><?php echo ucfirst($_SESSION['role']); ?></span>
            </div>
            <ul class="navbar-menu">
                <li><a href="?page=dashboard" class="nav-link">Dashboard</a></li>
                
                <?php if (in_array($_SESSION['role'], ['admin', 'staff'])): ?>
                    <li><a href="?page=sales&action=create" class="nav-link">New Sale</a></li>
                    <li><a href="?page=sales" class="nav-link">Sales</a></li>
                    <li><a href="?page=inventory" class="nav-link">Inventory</a></li>
                <?php endif; ?>
                
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li><a href="?page=products" class="nav-link">Products</a></li>
                    <li><a href="?page=users" class="nav-link">Users</a></li>
                <?php endif; ?>
                
                <li><a href="?page=logout" class="nav-link logout">Logout</a></li>
            </ul>
        </div>
    </nav>
