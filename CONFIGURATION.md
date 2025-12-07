# POS System Configuration Guide

## Database Configuration

Edit `src/Config/database.php` to match your environment:

```php
define('DB_HOST', 'localhost');      // MySQL Host
define('DB_USER', 'root');           // MySQL Username
define('DB_PASS', '');               // MySQL Password
define('DB_NAME', 'pos_system');     // Database Name
```

## Application Constants

Edit `src/Config/constants.php` to customize:

### User Roles
```php
define('ROLE_ADMIN', 'admin');       // Full access
define('ROLE_STAFF', 'staff');       // Sales & Inventory access
define('ROLE_CASHIER', 'cashier');   // Sales only
```

### Inventory Settings
```php
define('LOW_STOCK_THRESHOLD', 10);        // Items below this level show as low
define('CRITICAL_STOCK_THRESHOLD', 5);    // Items below this level show as critical
```

### Currency
```php
define('CURRENCY_SYMBOL', '$');      // Currency symbol for display
define('CURRENCY_CODE', 'USD');      // ISO currency code
```

### System Settings
```php
define('APP_NAME', 'POS System');
define('APP_VERSION', '1.0.0');
define('APP_TIMEZONE', 'UTC');
define('SESSION_TIMEOUT', 30);       // minutes
define('ITEMS_PER_PAGE', 20);        // Pagination
```

## Environment-Specific Setups

### Development (Local Machine)

1. Database: Local MySQL
2. PHP: Local installation
3. Server: PHP built-in server

```bash
cd public
php -S localhost:8000
```

### Production (XAMPP)

1. Database: XAMPP MySQL
2. PHP: XAMPP PHP
3. Server: Apache in XAMPP

**Virtual Host Setup:**
```apache
<VirtualHost *:80>
    ServerName pos.local
    DocumentRoot "C:/xampp/htdocs/pos/public"
    
    <Directory "C:/xampp/htdocs/pos/public">
        AllowOverride All
        Options +FollowSymLinks
        Require all granted
    </Directory>
    
    <IfModule mod_rewrite.c>
        RewriteEngine On
        RewriteBase /
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule . index.php [L]
    </IfModule>
</VirtualHost>
```

## Database Backup

### Backup Command
```bash
mysqldump -u root -p pos_system > backup_$(date +%Y%m%d_%H%M%S).sql
```

### Restore Command
```bash
mysql -u root -p pos_system < backup_file.sql
```

## File Permissions

Ensure proper permissions for:

```bash
# Allow write access to assets
chmod 755 public/assets
chmod 755 public/assets/css
chmod 755 public/assets/js
chmod 755 public/assets/images
```

## Security Configuration

### PHP Settings (php.ini)
```ini
; Increase upload limit if needed
upload_max_filesize = 100M
post_max_size = 100M

; Security settings
expose_php = Off
display_errors = Off
log_errors = On

; Session settings
session.cookie_httponly = On
session.use_strict_mode = On
```

### .htaccess (if using Apache with mod_rewrite)
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    
    # Prevent direct access to non-public files
    RewriteRule ^(?!public/) - [F]
    RewriteRule ^(src|database)/ - [F]
</IfModule>
```

## URL Configuration

### With Virtual Host
`http://pos.local/` (or your configured domain)

### Without Virtual Host
`http://localhost/pos/public/`

### PHP Built-in Server
`http://localhost:8000/`

## SMTP Configuration (For Future Email Features)

Future versions may support email receipts:

```php
// Example SMTP configuration
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USER', 'your-email@gmail.com');
define('MAIL_PASS', 'your-app-password');
define('MAIL_FROM', 'noreply@pos-system.local');
```

## Logging Configuration

Create a logs directory:
```bash
mkdir logs
chmod 755 logs
```

## Troubleshooting Configuration Issues

### Issue: "Connection Refused"
**Solution:** Check MySQL is running
```bash
# Check MySQL service status
net start MySQL80
# or
service mysql start
```

### Issue: "Access Denied"
**Solution:** Verify credentials in database.php
```bash
# Test connection
mysql -u root -p -e "SELECT 1;"
```

### Issue: "PHP Not Finding Extensions"
**Solution:** Check php.ini has PDO enabled
```ini
extension=php_pdo_mysql.dll
extension=php_pdo.dll
```

### Issue: "Session Not Working"
**Solution:** Check temp directory is writable
```bash
# Linux/Mac
chmod 777 /tmp

# Windows (usually automatic)
```

## Performance Optimization

### Database Optimization
```sql
-- Add indexes for better performance
ALTER TABLE sales ADD INDEX idx_date_cashier (created_at, cashier_id);
ALTER TABLE sale_items ADD INDEX idx_sale_product (sale_id, product_id);

-- Analyze tables
ANALYZE TABLE sales;
ANALYZE TABLE sale_items;
ANALYZE TABLE products;
```

### PHP Optimization
- Enable OPcache in php.ini
- Use persistent database connections
- Cache frequently accessed data

### Browser Cache
Edit .htaccess for better caching:
```apache
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
</IfModule>
```

## Maintenance Schedule

### Daily
- Monitor dashboard for sales
- Check low stock items
- Reconcile cash drawer (if applicable)

### Weekly
- Review sales reports
- Backup database
- Check system logs

### Monthly
- Inventory count
- Financial reconciliation
- User activity review

### Quarterly
- Database maintenance (OPTIMIZE tables)
- Performance analysis
- Security update check

---

**Last Updated:** December 2025
**System Version:** 1.0.0
