<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - POS System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include __DIR__ . '/layouts/header.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1>Dashboard</h1>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php 
                echo htmlspecialchars($_SESSION['success']);
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <div class="dashboard-grid">
            <!-- Sales Summary -->
            <div class="card">
                <div class="card-header">Today's Sales</div>
                <div class="card-body">
                    <div class="stat-value"><?php echo CURRENCY_SYMBOL . number_format($todaysSales, 2); ?></div>
                    <div class="stat-label"><?php echo $todaysCount; ?> transactions</div>
                </div>
            </div>

            <!-- Monthly Sales -->
            <div class="card">
                <div class="card-header">This Month's Sales</div>
                <div class="card-body">
                    <div class="stat-value"><?php echo CURRENCY_SYMBOL . number_format($monthSales, 2); ?></div>
                    <div class="stat-label">Monthly total</div>
                </div>
            </div>

            <!-- Inventory Status -->
            <div class="card">
                <div class="card-header">Inventory Value</div>
                <div class="card-body">
                    <div class="stat-value"><?php echo CURRENCY_SYMBOL . number_format($inventoryStatus['total_cost_value'] ?? 0, 2); ?></div>
                    <div class="stat-label"><?php echo $inventoryStatus['total_products'] ?? 0; ?> products</div>
                </div>
            </div>

            <!-- Low Stock Alert -->
            <div class="card alert-card">
                <div class="card-header">Low Stock Items</div>
                <div class="card-body">
                    <div class="stat-value" style="color: #ff6b6b;"><?php echo $inventoryStatus['low_stock_count'] ?? 0; ?></div>
                    <div class="stat-label">Items need reorder</div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="charts-row">
            <div class="card full-width">
                <div class="card-header">Sales Over Last 30 Days</div>
                <div class="card-body">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Sales -->
        <div class="card full-width">
            <div class="card-header">
                Recent Transactions
                <a href="?page=sales&action=history" class="card-link">View All</a>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Cashier</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Date/Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recentSales)): ?>
                            <?php foreach ($recentSales as $sale): ?>
                                <tr>
                                    <td><a href="?page=sales&action=receipt&id=<?php echo $sale['id']; ?>">#<?php echo $sale['id']; ?></a></td>
                                    <td><?php echo htmlspecialchars($sale['cashier_name'] ?? 'Unknown'); ?></td>
                                    <td><strong><?php echo CURRENCY_SYMBOL . number_format($sale['total_amount'], 2); ?></strong></td>
                                    <td><?php echo ucfirst(htmlspecialchars($sale['payment_method'])); ?></td>
                                    <td><?php echo date('M d, Y H:i', strtotime($sale['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No sales yet</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Low Stock Items -->
        <?php if (!empty($lowStockItems)): ?>
        <div class="card full-width">
            <div class="card-header">
                Low Stock Warning
                <a href="?page=inventory&action=low-stock" class="card-link">Manage Inventory</a>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Barcode</th>
                            <th>Current Stock</th>
                            <th>Reorder Level</th>
                            <th>Shortage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lowStockItems as $item): ?>
                                <tr style="background: #fff3cd;">
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo htmlspecialchars($item['sku']); ?></td>
                                <td><?php echo htmlspecialchars($item['barcode'] ?? ''); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo $item['reorder_level']; ?></td>
                                <td><strong><?php echo $item['shortage']; ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/layouts/footer.php'; ?>

    <script>
        // Sales Chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesData = <?php echo json_encode($dailySales); ?>;
        
        const labels = salesData.map(d => new Date(d.date).toLocaleDateString('en-US', {month: 'short', day: 'numeric'}));
        const amounts = salesData.map(d => parseFloat(d.total));
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels.reverse(),
                datasets: [{
                    label: 'Daily Sales',
                    data: amounts.reverse(),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toFixed(2);
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
