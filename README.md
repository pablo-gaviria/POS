# Point of Sale (POS) System

A comprehensive, easy-to-use Point of Sale system designed for small convenience stores and retail businesses. Built with PHP, MySQL, and vanilla JavaScript.

## Features

### Core Functionality
- ✅ **Dashboard** - Real-time sales tracking, inventory status, and low stock alerts
- ✅ **Sales Management** - Process transactions, manage payment methods, print receipts
- ✅ **Product Management** - Full CRUD operations, categorization, pricing
- ✅ **Inventory Tracking** - Stock levels, reorder points, low stock warnings
- ✅ **User Management** - Role-based access (Admin, Staff, Cashier)
- ✅ **Reporting** - Sales history, daily reports, inventory valuation

### Dashboard Highlights
- Today's sales amount and transaction count
- Monthly sales tracking
- Inventory value at cost
- Low stock item alerts
- 30-day sales chart
- Recent transaction history
- Top-selling products

### Role-Based Permissions
- **Admin**: Full system access, product/user management, reports
- **Staff**: Sales processing, inventory viewing, product browsing
- **Cashier**: Sales processing only, limited inventory access
- **User**: Anonymous login not allowed

### Inventory Management
- Real-time stock tracking
- Automatic stock deduction on sales
- Low stock alerts with priority levels
- Reorder level configuration
- Inventory movement history
- Cost-based valuation

## System Requirements

- **Web Server**: Apache with PHP 7.4+
- **Database**: MySQL 5.7+
- **PHP Extensions**: PDO, MySQLi
- **Browser**: Modern browser (Chrome, Firefox, Safari, Edge)

## Installation Steps

### 1. Setup Database

1. Create a new MySQL database:
```sql
CREATE DATABASE pos_system;
```

2. Import the schema:
```bash
mysql -u root -p pos_system < database/schema.sql
```

3. Verify tables were created:
```sql
SHOW TABLES;
```

### 2. Configure Database Connection

Edit `src/Config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
define('DB_NAME', 'pos_system');
```

### 3. Setup Web Server

For **Apache**:
```apache
<VirtualHost *:80>
    ServerName localhost
    DocumentRoot "C:/xampp/htdocs/pos/public"
    
    <Directory "C:/xampp/htdocs/pos/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

For **Local Development**:
```bash
cd public
php -S localhost:8000
```

### 4. Access the System

1. Open your browser and go to:
   - If using Apache: `http://localhost/pos`
   - If using PHP built-in: `http://localhost:8000`

2. Login with demo credentials:
   - **Admin**: admin@pos.com / password
   - **Staff**: staff@pos.com / password
   - **Cashier**: cashier@pos.com / password

## Project Structure

```
pos/
├── public/
│   ├── index.php              # Main entry point
│   └── assets/
│       ├── css/
│       │   └── style.css      # Main stylesheet
│       ├── js/                # JavaScript files
│       └── images/            # Product images
├── src/
│   ├── Config/
│   │   ├── database.php       # Database connection
│   │   └── constants.php      # Application constants
│   ├── Controllers/           # Business logic
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── ProductController.php
│   │   ├── InventoryController.php
│   │   ├── SalesController.php
│   │   ├── UserController.php
│   │   └── CashierController.php
│   ├── Models/               # Database models
│   │   ├── Database.php
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Sale.php
│   │   ├── SaleItem.php
│   │   └── Inventory.php
│   └── Views/                # HTML templates
│       ├── auth/
│       ├── layouts/
│       ├── products/
│       ├── inventory/
│       ├── sales/
│       ├── users/
│       ├── cashier/
│       └── dashboard.php
└── database/
    └── schema.sql            # Database schema

```

## Usage Guide

### Processing a Sale

1. Click "New Sale" from the menu
2. Search and select products
3. Adjust quantities in the cart
4. Select payment method (Cash, Card, Check)
5. Click "Complete Sale"
6. Print or save the receipt

### Managing Inventory

1. Go to "Inventory" menu
2. View all products with current stock levels
3. Check "Low Stock Items" for items below reorder level
4. Click "Reorder" to adjust stock levels
5. View inventory valuation at cost

### Managing Products (Admin Only)

1. Go to "Products" menu
2. Click "Add Product" to create new items
3. Fill in SKU, name, category, pricing, and stock levels
4. Edit existing products by clicking "Edit"
5. Delete products by clicking "Delete" (soft delete)

### Managing Users (Admin Only)

1. Go to "Users" menu
2. Click "Add User" to create new accounts
3. Assign roles (Admin, Staff, Cashier)
4. Edit user details and status
5. Delete users (cannot delete own account)

### Dashboard Analytics

- **Today's Sales**: Shows sales processed today
- **Monthly Sales**: Running total for the current month
- **Inventory Value**: Total cost value of all products
- **Low Stock**: Count of items below reorder level
- **Sales Chart**: 30-day sales trend visualization

## Demo Data

The system comes with 10 sample products:
- Beverages (Coca Cola, Pepsi, Water, Juice)
- Snacks (Chips, Chocolate Bar)
- Dairy (Milk, Butter)
- Bakery (Bread Loaf)
- Household (Coffee)

## Features by User Role

### Admin Features
- Full system access
- Product CRUD operations
- User management
- View all reports
- Inventory management
- Sales history

### Staff Features
- Process sales
- View inventory
- Browse products
- View sales history
- Check low stock items

### Cashier Features
- Process sales only
- Limited inventory view
- View own transactions
- Print receipts

## Security Features

- Password hashing with bcrypt
- Session-based authentication
- Role-based access control
- SQL injection protection (prepared statements)
- CSRF protection ready
- XSS protection with htmlspecialchars()

## Tips for Best Use

1. **Regular Backups**: Backup your database regularly
2. **Stock Control**: Update inventory levels daily
3. **User Access**: Create separate accounts for each staff member
4. **Product Categories**: Organize products by category for easier search
5. **Reorder Levels**: Set appropriate reorder levels for each product
6. **Payment Methods**: Adjust payment methods in the sale form as needed
7. **Reports**: Check daily/monthly reports for business insights

## Troubleshooting

### Database Connection Error
- Verify MySQL is running
- Check database credentials in `src/Config/database.php`
- Ensure database `pos_system` exists
- Check user privileges

### Login Failed
- Verify database contains user records
- Check user status is 'active'
- Ensure password is correct (case-sensitive)
- Clear browser cookies and try again

### Products Not Showing
- Verify products table has data
- Check products are not deleted (deleted_at is NULL)
- Ensure database connection is working

### Low Stock Not Showing
- Verify current quantity is less than reorder level
- Check product status is 'active'
- Refresh the page

## Development Notes

- The system uses PDO for database operations
- MySQLi is available as fallback
- No external dependencies required (vanilla PHP)
- Chart.js is loaded from CDN for analytics
- All styling is custom CSS (no Bootstrap required)
- Responsive design for desktop and tablets

## Future Enhancements

- Barcode scanning
- Customer loyalty program
- Multi-location support
- Advanced reporting and analytics
- Email receipt delivery
- Mobile app
- Payment gateway integration
- Tax management
- Supplier management
- Purchase orders

## Support

For issues or questions:
1. Check the troubleshooting section
2. Verify all system requirements are met
3. Check database schema is correctly imported
4. Ensure all files are in the correct directories

## License

This Point of Sale system is provided for local use only on a single machine. Do not distribute or modify without permission.

## Version

**Version 1.0.0** - Initial Release
- December 2025

---

**Local Use Only** - This system is designed for use on a local network or single machine in a convenience store or small retail business.
