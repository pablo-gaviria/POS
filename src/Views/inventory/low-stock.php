<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Low Stock Items - POS System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1>Low Stock Items</h1>
            <a href="?page=inventory" class="btn btn-secondary">Back to Inventory</a>
        </div>

        <div class="card full-width">
            <div class="card-header" style="color: #ff6b6b;">Items Below Reorder Level</div>
            <div class="card-body">
                <?php if (!empty($lowStockItems)): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Barcode</th>
                                <th>Product Name</th>
                                <th>Current Stock</th>
                                <th>Reorder Level</th>
                                <th>Shortage (Units)</th>
                                <th>Priority</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lowStockItems as $item): ?>
                                <tr style="background: #fff5f5;">
                                    <td><strong><?php echo htmlspecialchars($item['sku']); ?></strong></td>
                                    <td><strong><?php echo htmlspecialchars($item['barcode'] ?? ''); ?></strong></td>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td><span class="badge badge-danger"><?php echo $item['quantity']; ?></span></td>
                                    <td><?php echo $item['reorder_level']; ?></td>
                                    <td><strong style="color: #c33;"><?php echo $item['shortage']; ?></strong></td>
                                    <td>
                                        <?php 
                                        if ($item['quantity'] == 0) {
                                            echo '<span class="badge" style="background: #c33; color: white;">CRITICAL</span>';
                                        } elseif ($item['quantity'] <= $item['reorder_level'] / 2) {
                                            echo '<span class="badge badge-danger">HIGH</span>';
                                        } else {
                                            echo '<span class="badge badge-warning">MEDIUM</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="?page=products&action=edit&id=<?php echo $item['id']; ?>" class="btn-small btn-edit">Reorder</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="padding: 20px; text-align: center; background: #f0fff0; border-radius: 5px;">
                        <p style="color: #2d7a2d; font-size: 16px;">✓ All products are well stocked!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>
