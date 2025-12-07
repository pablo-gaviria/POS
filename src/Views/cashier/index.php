<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Dashboard - POS System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1>Cashier Dashboard</h1>
        </div>

        <div class="dashboard-grid">
            <div class="card">
                <div class="card-header">Quick Actions</div>
                <div class="card-body">
                    <a href="?page=sales&action=create" class="btn btn-primary" style="width: 100%; margin-bottom: 10px;">New Sale</a>
                    <a href="?page=sales" class="btn btn-info" style="width: 100%; margin-bottom: 10px;">View Sales</a>
                    <a href="?page=inventory" class="btn btn-warning" style="width: 100%;">Check Inventory</a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Shift Information</div>
                <div class="card-body">
                    <p><strong>Shift Status:</strong> <span class="badge badge-success">Active</span></p>
                    <p><strong>Shift Start:</strong> <?php echo date('M d, Y H:i'); ?></p>
                    <p><strong>Your ID:</strong> <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
                </div>
            </div>
        </div>

        <div class="card full-width">
            <div class="card-header">Cashier Help</div>
            <div class="card-body">
                <p>Use this dashboard to process sales and manage your shift. For additional features, contact your administrator.</p>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>
