<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - POS System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .receipt {
            max-width: 400px;
            background: white;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 20px auto;
            font-family: 'Courier New', monospace;
            font-size: 13px;
        }
        .receipt-header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .receipt-header h2 {
            margin: 0;
            font-size: 18px;
        }
        .receipt-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }
        .receipt-separator {
            border-top: 1px dashed #000;
            padding: 5px 0;
        }
        .receipt-total {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 10px 0;
            margin: 10px 0;
            font-weight: bold;
        }
        .receipt-footer {
            text-align: center;
            padding-top: 10px;
            font-size: 11px;
            color: #666;
        }
        .print-button {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1>Sale Receipt</h1>
            <a href="?page=sales" class="btn btn-secondary">Back to Sales</a>
        </div>

        <div class="receipt">
            <div class="receipt-header">
                <h2>POS System</h2>
                <p>Receipt</p>
            </div>

            <div class="receipt-item">
                <span>Transaction ID:</span>
                <span>#<?php echo $sale['id']; ?></span>
            </div>
            <div class="receipt-item">
                <span>Cashier:</span>
                <span><?php echo htmlspecialchars($sale['cashier_name'] ?? 'System'); ?></span>
            </div>
            <div class="receipt-item">
                <span>Date/Time:</span>
                <span><?php echo date('M d, Y H:i:s', strtotime($sale['created_at'])); ?></span>
            </div>
            <div class="receipt-item">
                <span>Payment:</span>
                <span><?php echo ucfirst(htmlspecialchars($sale['payment_method'])); ?></span>
            </div>

            <div class="receipt-separator"></div>

            <?php if (!empty($items)): ?>
                <div style="margin-bottom: 10px;">
                    <?php foreach ($items as $item): ?>
                        <div class="receipt-item" style="border-bottom: 1px dashed #eee; padding-bottom: 8px; margin-bottom: 8px;">
                            <div style="flex: 1;">
                                <div><?php echo htmlspecialchars($item['name']); ?></div>
                                <div style="font-size: 11px; color: #666;">
                                    <?php echo $item['quantity']; ?> x <?php echo CURRENCY_SYMBOL . number_format($item['unit_price'], 2); ?>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <?php echo CURRENCY_SYMBOL . number_format($item['subtotal'], 2); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="receipt-total">
                <div class="receipt-item">
                    <span>TOTAL</span>
                    <span><?php echo CURRENCY_SYMBOL . number_format($sale['total_amount'], 2); ?></span>
                </div>
            </div>

            <div class="receipt-footer">
                <p>Thank you for your purchase!</p>
                <p>POS System - Local Use</p>
            </div>
        </div>

        <div class="print-button">
            <button onclick="window.print()" class="btn btn-primary">Print Receipt</button>
            <a href="?page=sales&action=create" class="btn btn-secondary">New Sale</a>
        </div>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>
