# Quick Start Guide - POS System

## 5-Minute Quick Setup

### Step 1: Create Database (1 min)
```bash
mysql -u root -p
```
```sql
CREATE DATABASE pos_system;
```

### Step 2: Import Schema (1 min)
```bash
mysql -u root -p pos_system < database/schema.sql
```

### Step 3: Configure Database (1 min)
Edit `src/Config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'pos_system');
```

### Step 4: Run Server (1 min)
```bash
cd public
php -S localhost:8000
```

### Step 5: Login (1 min)
- Open: `http://localhost:8000`
- Email: `admin@pos.com`
- Password: `password`

---

## First Actions After Login

1. **Add Your Products**
   - Click Products menu
   - Click "Add Product"
   - Enter: SKU, Name, Category, Price, Cost, Quantity, Reorder Level
   - Repeat for each product

2. **Create Staff Accounts**
   - Click Users menu (Admin only)
   - Click "Add User"
   - Enter name, email, password
   - Select role (Staff or Cashier)
   - Click Create

3. **Process a Sale**
   - Click "New Sale"
   - Search for products
   - Click products to add to cart
   - Adjust quantities as needed
   - Select payment method
   - Click "Complete Sale"

4. **Check Dashboard**
   - View today's sales
   - See low stock items
   - Check inventory value
   - Review sales trend

---

## Key Menu Items by Role

### Admin Access
- Dashboard
- New Sale
- Sales History
- Inventory
- Products (CRUD)
- Users (CRUD)

### Staff Access
- Dashboard
- New Sale
- Sales History
- Inventory
- (No product/user management)

### Cashier Access
- Dashboard
- New Sale
- (Limited inventory view)

---

## Important Features

### Processing Sales
```
1. Click "New Sale"
2. Search products by name or SKU
3. Click product to add
4. Adjust quantity in cart
5. Select payment method
6. Click "Complete Sale"
7. Print or close receipt
```

### Managing Inventory
```
1. Click "Inventory"
2. View all products with stock levels
3. Check "Low Stock Items" for urgent restocking
4. Stock decreases automatically when sales occur
```

### Managing Products (Admin)
```
1. Click "Products"
2. See all products with pricing
3. Click "Edit" to change prices/stock
4. Click "Delete" to remove product
5. Reorder level controls low stock alert
```

### Managing Users (Admin)
```
1. Click "Users"
2. See all staff accounts
3. Click "Edit" to change role or deactivate
4. Click "Delete" to remove user
5. Cannot delete your own account
```

---

## Dashboard Metrics Explained

- **Today's Sales**: Cash amount from transactions processed today
- **This Month's Sales**: Total sales for current calendar month
- **Inventory Value**: Total cost value of all products on hand
- **Low Stock Items**: Count of products below their reorder level
- **Sales Chart**: Daily sales trend for last 30 days

---

## Troubleshooting

| Problem | Solution |
|---------|----------|
| Can't login | Check MySQL running, verify credentials, verify database imported |
| Products not showing | Verify products in database, check status is 'active' |
| Low stock not showing | Current quantity must be LESS than reorder level |
| Can't complete sale | Cart must have at least one item |
| Receipt not printing | Use browser print (Ctrl+P) or print button |

---

## Demo Data Included

**Sample Products (10 total):**
- Coca Cola 330ml - $1.50
- Pepsi 500ml - $2.00
- Chips Pack - $1.25
- Chocolate Bar - $1.50
- Milk 1L - $2.50
- Bread Loaf - $2.00
- Butter 250g - $4.50
- Orange Juice - $3.50
- Mineral Water 2L - $1.00
- Coffee 500g - $5.00

**Demo Users:**
- admin@pos.com / password (Admin role)
- staff@pos.com / password (Staff role)
- cashier@pos.com / password (Cashier role)

---

## Configuration Files to Know

- **src/Config/database.php** - Database connection
- **src/Config/constants.php** - System settings, currency, thresholds
- **database/schema.sql** - Database structure
- **public/assets/css/style.css** - Application styling
- **INSTALLATION.md** - Full installation guide
- **CONFIGURATION.md** - Advanced configuration

---

## Regular Maintenance

### Daily
- Check low stock alerts
- Review today's sales
- Ensure all transactions completed

### Weekly
- Backup database
- Review sales reports
- Check inventory accuracy

### Monthly
- Physical inventory count
- Reconcile with system
- Update reorder levels as needed

---

## Next Steps

1. ✅ Create database
2. ✅ Import schema
3. ✅ Configure database.php
4. ✅ Start web server
5. ✅ Login with demo account
6. ⏭️ Add your products
7. ⏭️ Create staff accounts
8. ⏭️ Start processing sales

---

## Getting Help

1. Check README.md for detailed documentation
2. Review INSTALLATION.md for setup help
3. Check CONFIGURATION.md for settings
4. Review controller files for business logic
5. Examine view files for HTML structure

---

**POS System Ready to Use!** 🎉

For detailed documentation, see: README.md
