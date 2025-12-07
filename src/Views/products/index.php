<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - POS System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .products-container {
            background: #f5f7fa;
            padding: 20px 0;
            margin: 0 -20px -20px -20px;
        }

        .category-section {
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .category-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 14px 20px;
            margin: -20px -20px 20px -20px;
            border-radius: 8px 8px 0 0;
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .category-count {
            background: rgba(255, 255, 255, 0.3);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
        }

        .products-table thead {
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }

        .products-table thead th {
            padding: 16px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: #495057;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .products-table tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: background 0.2s;
        }

        .products-table tbody tr:hover {
            background: #f8f9fa;
        }

        .products-table td {
            padding: 14px 12px;
            font-size: 14px;
            color: #212529;
            vertical-align: middle;
        }

        .products-table td:first-child {
            font-weight: 500;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 4px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
        }

        .btn-edit {
            background: #667eea;
            color: white;
        }

        .btn-edit:hover {
            background: #5568d3;
        }

        .btn-delete {
            background: #ff6b6b;
            color: white;
        }

        .btn-delete:hover {
            background: #ee5a52;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }

        .empty-state p {
            font-size: 16px;
            margin: 0;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1>Products</h1>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="?page=products&action=create" class="btn btn-primary">+ Add Product</a>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php 
                echo htmlspecialchars($_SESSION['success']);
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php 
                echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Search Bar -->
        <div style="margin-bottom: 25px;">
            <input type="text" id="searchInput" placeholder="🔍 Search by product name, SKU, or barcode..." style="width: 100%; padding: 14px 16px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 14px; transition: border-color 0.3s; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);">
        </div>

        <div class="products-container">
            <?php 
            // Group products by category
            $productsByCategory = [];
            foreach ($products as $product) {
                $category = $product['category'] ?? 'Uncategorized';
                if (!isset($productsByCategory[$category])) {
                    $productsByCategory[$category] = [];
                }
                $productsByCategory[$category][] = $product;
            }
            
            // Display products grouped by category
            foreach ($productsByCategory as $category => $categoryProducts):
            ?>
                <div class="category-section">
                    <div class="category-header">
                        <span><?php echo htmlspecialchars($category); ?></span>
                        <span class="category-count"><?php echo count($categoryProducts); ?> products</span>
                    </div>
                    <table class="products-table">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Barcode</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Cost</th>
                                <th>Quantity</th>
                                <th>Reorder Level</th>
                                <th>Status</th>
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <th style="text-align: center;">Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categoryProducts as $product): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['sku']); ?></td>
                                    <td><?php echo htmlspecialchars($product['barcode'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td><?php echo CURRENCY_SYMBOL . number_format($product['price'], 2); ?></td>
                                    <td><?php echo CURRENCY_SYMBOL . number_format($product['cost'], 2); ?></td>
                                    <td>
                                        <span class="badge <?php echo $product['quantity'] <= $product['reorder_level'] ? 'badge-warning' : 'badge-success'; ?>">
                                            <?php echo $product['quantity']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $product['reorder_level']; ?></td>
                                    <td><span class="badge badge-info"><?php echo ucfirst($product['status']); ?></span></td>
                                    <?php if ($_SESSION['role'] === 'admin'): ?>
                                        <td class="action-buttons">
                                            <a href="?page=products&action=edit&id=<?php echo $product['id']; ?>" class="btn-small btn-edit">Edit</a>
                                            <a href="?page=products&action=delete&id=<?php echo $product['id']; ?>" class="btn-small btn-delete" onclick="return confirm('Delete this product?');">Delete</a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>

                <?php if (empty($products)): ?>
                    <div class="text-center" style="padding: 30px;">
                        <p>No products found</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
    
    <script>
        const searchInput = document.getElementById('searchInput');
        const categorySections = document.querySelectorAll('.category-section');
        
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            
            // Search through all category sections
            categorySections.forEach(section => {
                const table = section.querySelector('.products-table');
                const rows = table.querySelectorAll('tbody tr');
                let haVisibleRows = false;
                
                rows.forEach(row => {
                    // Get SKU, Barcode, Name columns
                    const sku = row.cells[0]?.textContent.toLowerCase() || '';
                    const barcode = row.cells[1]?.textContent.toLowerCase() || '';
                    const name = row.cells[2]?.textContent.toLowerCase() || '';
                    
                    const isMatch = sku.includes(searchTerm) || 
                                   barcode.includes(searchTerm) || 
                                   name.includes(searchTerm);
                    
                    row.style.display = isMatch ? 'table-row' : 'none';
                    if (isMatch) {
                        haVisibleRows = true;
                    }
                });
                
                // Show/hide entire category section based on whether it has matching products
                section.style.display = haVisibleRows ? 'block' : 'none';
            });
        });
    </script>
</body>
</html>
