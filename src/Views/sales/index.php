<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales - POS System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1>Sales Transactions</h1>
            <a href="?page=sales&action=create" class="btn btn-primary">New Sale</a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php 
                echo htmlspecialchars($_SESSION['success']);
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <div class="card full-width">
            <div class="card-header">Recent Sales</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Cashier</th>
                            <th>Total Amount</th>
                            <th>Payment Method</th>
                            <th>Date/Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($sales)): ?>
                            <?php foreach ($sales as $sale): ?>
                                <tr>
                                    <td><strong>#<?php echo $sale['id']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($sale['cashier_name'] ?? 'Unknown'); ?></td>
                                    <td><strong><?php echo CURRENCY_SYMBOL . number_format($sale['total_amount'], 2); ?></strong></td>
                                    <td><?php echo ucfirst(htmlspecialchars($sale['payment_method'])); ?></td>
                                    <td><?php echo date('M d, Y H:i', strtotime($sale['created_at'])); ?></td>
                                    <td>
                                        <a href="?page=sales&action=receipt&id=<?php echo $sale['id']; ?>" class="btn-small btn-info">View Receipt</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No sales recorded</td>
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
