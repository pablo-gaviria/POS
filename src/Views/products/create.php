<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - POS System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1>Add New Product</h1>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php 
                echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="?page=products&action=store" class="form" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="sku">SKU *</label>
                            <input type="text" id="sku" name="sku" value="<?php echo htmlspecialchars($nextSku ?? ''); ?>" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="name">Product Name *</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="category">Category *</label>
                            <div style="display: flex; gap: 10px; align-items: flex-end;">
                                <div style="flex: 1;">
                                    <select id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?php echo htmlspecialchars($cat['name']); ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-sm btn-success" onclick="openAddCategoryModal()">+ Add</button>
                            </div>
                            <div id="categories-list" style="margin-top: 10px; padding: 10px; background: #f9f9f9; border-radius: 4px; max-height: 200px; overflow-y: auto;">
                                <strong>Available Categories:</strong>
                                <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px;">
                                    <?php foreach ($categories as $cat): ?>
                                        <div style="background: #e8f5e9; padding: 6px 10px; border-radius: 4px; display: flex; align-items: center; gap: 8px;">
                                            <span><?php echo htmlspecialchars($cat['name']); ?></span>
                                            <button type="button" class="btn btn-xs btn-danger" onclick="deleteCategory('<?php echo htmlspecialchars($cat['name']); ?>')" style="padding: 2px 6px; font-size: 12px;">✕</button>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="barcode">Barcode (optional)</label>
                            <input type="number" id="barcode" name="barcode">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">Product Image (optional)</label>
                            <input type="file" id="image" name="image" accept="image/*" style="padding: 5px;">
                            <small style="color: #666;">Max 5MB. Formats: JPG, PNG, GIF, WebP</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Selling Price *</label>
                            <input type="number" id="price" name="price" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="cost">Cost Price *</label>
                            <input type="number" id="cost" name="cost" step="0.01" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="quantity">Initial Quantity *</label>
                            <input type="number" id="quantity" name="quantity" value="0" required>
                        </div>
                        <div class="form-group">
                            <label for="reorder_level">Reorder Level *</label>
                            <input type="number" id="reorder_level" name="reorder_level" value="10" required>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Create Product</button>
                        <a href="?page=products" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div id="addCategoryModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: white; padding: 30px; border-radius: 8px; width: 90%; max-width: 400px; box-shadow: 0 2px 10px rgba(0,0,0,0.2);">
            <h2>Add New Category</h2>
            <form id="addCategoryForm" style="margin-top: 20px;">
                <div class="form-group">
                    <label for="newCategoryName">Category Name *</label>
                    <input type="text" id="newCategoryName" name="name" required>
                </div>
                <div class="form-group">
                    <label for="newCategoryDesc">Description</label>
                    <textarea id="newCategoryDesc" name="description" rows="3"></textarea>
                </div>
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">Add Category</button>
                    <button type="button" class="btn btn-secondary" onclick="closeAddCategoryModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
    
    <script>
        function openAddCategoryModal() {
            document.getElementById('addCategoryModal').style.display = 'flex';
        }

        function closeAddCategoryModal() {
            document.getElementById('addCategoryModal').style.display = 'none';
            document.getElementById('addCategoryForm').reset();
        }

        document.getElementById('addCategoryForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = document.getElementById('newCategoryName').value;
            const description = document.getElementById('newCategoryDesc').value;

            fetch('?page=categories&action=store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'name=' + encodeURIComponent(name) + '&description=' + encodeURIComponent(description)
            })
            .then(response => response.text())
            .then(data => {
                // Reload the page to show new category
                location.reload();
            })
            .catch(error => {
                alert('Error adding category: ' + error);
            });
        });

        function deleteCategory(categoryName) {
            if (confirm('Delete category "' + categoryName + '"?')) {
                fetch('?page=categories&action=delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'name=' + encodeURIComponent(categoryName)
                })
                .then(response => response.text())
                .then(data => {
                    location.reload();
                })
                .catch(error => {
                    alert('Error deleting category: ' + error);
                });
            }
        }
    </script>
</body>
</html>
