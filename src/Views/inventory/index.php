<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory - POS System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1>Inventory Management</h1>
            <div style="display: flex; gap: 10px;">
                <a href="?page=inventory&action=low-stock" class="btn btn-warning">Low Stock Items</a>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="?page=products&action=create" class="btn btn-primary">Add Product</a>
                <?php endif; ?>
            </div>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php 
                echo htmlspecialchars($_SESSION['success']);
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Inventory Summary -->
        <div class="dashboard-grid">
            <div class="card">
                <div class="card-header">Total Products</div>
                <div class="card-body">
                    <div class="stat-value"><?php echo $inventoryStatus['total_products'] ?? 0; ?></div>
                    <div class="stat-label">SKUs in system</div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Total Units</div>
                <div class="card-body">
                    <div class="stat-value"><?php echo number_format($inventoryStatus['total_quantity'] ?? 0); ?></div>
                    <div class="stat-label">Total quantity on hand</div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Inventory Value</div>
                <div class="card-body">
                    <div class="stat-value"><?php echo CURRENCY_SYMBOL . number_format($inventoryStatus['total_cost_value'] ?? 0, 2); ?></div>
                    <div class="stat-label">At cost</div>
                </div>
            </div>

            <div class="card alert-card">
                <div class="card-header">Low Stock</div>
                <div class="card-body">
                    <div class="stat-value" style="color: #ff6b6b;"><?php echo $inventoryStatus['low_stock_count'] ?? 0; ?></div>
                    <div class="stat-label">Items below reorder level</div>
                </div>
            </div>
        </div>

        <!-- Inventory Table -->
        <div class="card full-width">
            <div class="card-header">Product Inventory</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                            <tr>
                            <th>SKU</th>
                            <th>Barcode</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Current Stock</th>
                            <th>Reorder Level</th>
                            <th>Status</th>
                            <th>Unit Cost</th>
                            <th>Total Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['sku']); ?></td>
                                    <td><?php echo htmlspecialchars($product['barcode'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td><?php echo htmlspecialchars($product['category']); ?></td>
                                    <td><?php echo number_format($product['quantity']); ?></td>
                                    <td><?php echo $product['reorder_level']; ?></td>
                                    <td>
                                        <?php if ($product['quantity'] <= $product['reorder_level']): ?>
                                            <span class="badge badge-danger">Low Stock</span>
                                        <?php else: ?>
                                            <span class="badge badge-success">OK</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo CURRENCY_SYMBOL . number_format($product['cost'], 2); ?></td>
                                    <td><?php echo CURRENCY_SYMBOL . number_format($product['quantity'] * $product['cost'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No products in inventory</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>
