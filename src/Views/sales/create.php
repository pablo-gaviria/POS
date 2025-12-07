<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Sale - POS System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .sales-container {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 20px;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .product-item {
            padding: 12px;
            border: 2px solid #ddd;
            margin-bottom: 0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
            text-align: center;
            display: flex;
            flex-direction: column;
        }
        
        .product-item:hover {
            border-color: #667eea;
            background: #f0f4ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
        }
        
        .product-item > div:not([style]) {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 14px;
        }
        
        .product-item .barcode {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .product-item .stock {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .product-item .price {
            color: #667eea;
            font-weight: bold;
            font-size: 15px;
        }

        @media (max-width: 768px) {
            .sales-container {
                grid-template-columns: 1fr;
            }
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1>Process New Sale</h1>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php 
                echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <div class="sales-container">
            <!-- Products Section -->
            <div class="card">
                <div class="card-header">Select Products</div>
                <div class="card-body">
                    <div style="margin-bottom: 15px;">
                        <input type="text" id="productSearch" placeholder="Search products..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    
                    <div id="productsList">
                        <!-- Products will be displayed here organized by category -->
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
                            <div style="margin-bottom: 20px;">
                                <h3 style="background: #667eea; color: white; padding: 10px; margin: 0 0 10px 0; border-radius: 5px;">
                                    <?php echo htmlspecialchars($category); ?>
                                </h3>
                                <div class="products-grid">
                                    <?php foreach ($categoryProducts as $product): ?>
                                        <div class="product-item" 
                                             data-id="<?php echo $product['id']; ?>" 
                                             data-sku="<?php echo htmlspecialchars($product['sku']); ?>"
                                             data-barcode="<?php echo htmlspecialchars($product['barcode'] ?? ''); ?>"
                                             data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                             data-price="<?php echo $product['price']; ?>">
                                            <?php if (!empty($product['image'])): ?>
                                                <div style="width: 100%; height: 80px; margin-bottom: 8px; overflow: hidden; border-radius: 4px;">
                                                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                            <?php else: ?>
                                                <div style="width: 100%; height: 80px; margin-bottom: 8px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999; font-size: 12px;">
                                                    No Image
                                                </div>
                                            <?php endif; ?>
                                            <div><?php echo htmlspecialchars(substr($product['name'], 0, 20)); ?></div>
                                            <div class="sku"><?php echo htmlspecialchars($product['sku']); ?></div>
                                            <div class="barcode"><?php echo htmlspecialchars($product['barcode'] ?? ''); ?></div>
                                            <div class="stock">Stock: <?php echo $product['quantity']; ?></div>
                                            <div class="price"><?php echo CURRENCY_SYMBOL . number_format($product['price'], 2); ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Cart Section -->
            <div class="card" style="height: fit-content; position: sticky; top: 100px;">
                <div class="card-header">Cart</div>
                <div class="card-body">
                    <div id="cartItems" style="max-height: 300px; overflow-y: auto; margin-bottom: 15px;">
                        <!-- Cart items will be added here -->
                    </div>
                    
                    <div style="border-top: 2px solid #eee; padding-top: 10px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Subtotal:</span>
                            <span id="subtotal"><?php echo CURRENCY_SYMBOL . '0.00'; ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 18px; margin-bottom: 15px;">
                            <span>Total:</span>
                            <span id="total"><?php echo CURRENCY_SYMBOL . '0.00'; ?></span>
                        </div>
                    </div>

                    <form method="POST" action="?page=sales&action=store" id="saleForm" style="margin-top: 15px;">
                        <div style="margin-bottom: 10px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Payment Method:</label>
                            <select name="payment_method" id="paymentMethod" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px;">
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="check">Check</option>
                            </select>
                        </div>

                        <!-- Cashier Management Section -->
                        <div id="paymentSection" style="background: #f5f5f5; padding: 10px; border-radius: 5px; margin-bottom: 10px;">
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: bold; font-size: 13px;">Amount Paid:</label>
                                <input type="number" id="amountPaid" name="amount_paid" step="0.01" min="0" placeholder="0.00" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
                            </div>
                            <div style="background: white; padding: 10px; border-radius: 5px; border: 2px solid #667eea;">
                                <div style="font-size: 12px; color: #666; margin-bottom: 5px;">Change Due:</div>
                                <div style="font-size: 24px; font-weight: bold; color: #667eea;">
                                    <span id="changeDue"><?php echo CURRENCY_SYMBOL . '0.00'; ?></span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 16px; font-weight: bold;">Complete Sale</button>
                    </form>

                    <button type="button" onclick="clearCart()" class="btn btn-secondary" style="width: 100%; margin-top: 5px;">Clear Cart</button>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>

    <script>
        let cartItems = [];
        const CURRENCY_SYMBOL = '<?php echo CURRENCY_SYMBOL; ?>';

        // Add to cart - fixed event listener
        document.querySelectorAll('.product-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.getAttribute('data-id');
                const productName = this.getAttribute('data-name');
                const productPrice = parseFloat(this.getAttribute('data-price'));
                const productSku = this.getAttribute('data-sku');
                const productBarcode = this.getAttribute('data-barcode');

                // Check if product already in cart
                const existingItem = cartItems.find(i => i.productId == productId);
                if (existingItem) {
                    existingItem.quantity++;
                } else {
                    cartItems.push({
                        productId: productId,
                        productName: productName,
                        productSku: productSku,
                        productBarcode: productBarcode,
                        price: productPrice,
                        quantity: 1
                    });
                }
                updateCart();
            });
        });

        function updateCart() {
            const cartDiv = document.getElementById('cartItems');
            cartDiv.innerHTML = '';
            let total = 0;

            if (cartItems.length === 0) {
                cartDiv.innerHTML = '<p style="text-align: center; color: #999;">Cart is empty</p>';
            } else {
                cartItems.forEach((item, index) => {
                    const subtotal = item.price * item.quantity;
                    total += subtotal;

                    cartDiv.innerHTML += `
                        <div style="padding: 8px; background: #f9f9f9; margin-bottom: 8px; border-radius: 5px; font-size: 13px;">
                            <div style="font-weight: bold; margin-bottom: 3px;">${item.productName}</div>
                            <div style="font-size: 12px; color: #666; margin-bottom: 4px;">SKU: ${item.productSku || '-'} ${item.productBarcode ? '| Barcode: ' + item.productBarcode : ''}</div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 3px;">
                                <span>${CURRENCY_SYMBOL}${item.price.toFixed(2)}</span>
                                <input type="number" value="${item.quantity}" min="1" max="999" onchange="updateQuantity(${index}, this.value)" style="width: 50px; padding: 3px; border: 1px solid #ddd; border-radius: 3px;">
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>${CURRENCY_SYMBOL}${subtotal.toFixed(2)}</span>
                                <button onclick="removeFromCart(${index})" style="background: #ff6b6b; color: white; border: none; padding: 2px 8px; border-radius: 3px; cursor: pointer; font-size: 11px;">Remove</button>
                            </div>
                        </div>
                    `;
                });
            }

            document.getElementById('subtotal').textContent = CURRENCY_SYMBOL + total.toFixed(2);
            document.getElementById('total').textContent = CURRENCY_SYMBOL + total.toFixed(2);

            // Update hidden form inputs
            updateFormInputs();
        }

        function updateQuantity(index, quantity) {
            cartItems[index].quantity = parseInt(quantity);
            updateCart();
        }

        function removeFromCart(index) {
            cartItems.splice(index, 1);
            updateCart();
        }

        function clearCart() {
            cartItems = [];
            updateCart();
        }

        function updateFormInputs() {
            const form = document.getElementById('saleForm');
            const existingItems = form.querySelectorAll('[name^="items"]');
            existingItems.forEach(item => item.remove());

            cartItems.forEach((item, index) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `items[${index}][product_id]`;
                input.value = item.productId;
                form.appendChild(input);

                const qtyInput = document.createElement('input');
                qtyInput.type = 'hidden';
                qtyInput.name = `items[${index}][quantity]`;
                qtyInput.value = item.quantity;
                form.appendChild(qtyInput);

                const priceInput = document.createElement('input');
                priceInput.type = 'hidden';
                priceInput.name = `items[${index}][price]`;
                priceInput.value = item.price;
                form.appendChild(priceInput);

                const subtotalInput = document.createElement('input');
                subtotalInput.type = 'hidden';
                subtotalInput.name = `items[${index}][subtotal]`;
                subtotalInput.value = item.price * item.quantity;
                form.appendChild(subtotalInput);
            });
        }

        // Calculate change due when amount paid changes
        let totalAmount = 0;
        document.getElementById('amountPaid').addEventListener('input', function() {
            const amountPaid = parseFloat(this.value) || 0;
            const change = amountPaid - totalAmount;
            document.getElementById('changeDue').textContent = CURRENCY_SYMBOL + Math.max(0, change).toFixed(2);
        });

        // Update total amount when cart changes
        const originalUpdateCart = updateCart;
        updateCart = function() {
            originalUpdateCart();
            let total = 0;
            cartItems.forEach(item => {
                total += item.price * item.quantity;
            });
            totalAmount = total;
            document.getElementById('amountPaid').value = '';
            document.getElementById('changeDue').textContent = CURRENCY_SYMBOL + '0.00';
        };

        // Search products
        document.getElementById('productSearch').addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            // If scanner sends Enter after barcode, add matching product immediately
            if (e.key === 'Enter') {
                const exact = searchTerm.trim();
                if (exact.length > 0) {
                    const match = Array.from(document.querySelectorAll('.product-item')).find(i => (i.getAttribute('data-barcode')||'') === exact);
                    if (match) {
                        match.click();
                        this.value = '';
                        return;
                    }
                }
            }

            document.querySelectorAll('.product-item').forEach(item => {
                const name = (item.getAttribute('data-name') || '').toLowerCase();
                const barcode = (item.getAttribute('data-barcode') || '').toString().toLowerCase();
                const sku = (item.getAttribute('data-sku') || '').toString().toLowerCase();
                item.style.display = name.includes(searchTerm) || barcode.includes(searchTerm) || sku.includes(searchTerm) ? '' : 'none';
            });
        });

        // Prevent submit without items
        document.getElementById('saleForm').addEventListener('submit', function(e) {
            if (cartItems.length === 0) {
                e.preventDefault();
                alert('Please add items to the sale');
            }
        });
    </script>
</body>
</html>
