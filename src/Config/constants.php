<?php
// Application Constants

// User Roles
define('ROLE_ADMIN', 'admin');
define('ROLE_STAFF', 'staff');
define('ROLE_CASHIER', 'cashier');

// Stock Levels
define('LOW_STOCK_THRESHOLD', 10);
define('CRITICAL_STOCK_THRESHOLD', 5);

// Currency
define('CURRENCY_SYMBOL', '₱');
define('CURRENCY_CODE', 'PHP');

// Application Settings
define('APP_NAME', 'POS System');
define('APP_VERSION', '1.0.0');
define('APP_TIMEZONE', 'UTC');

// Pagination
define('ITEMS_PER_PAGE', 20);

// Session timeout (in minutes)
define('SESSION_TIMEOUT', 30);

// Upload paths
define('UPLOAD_PATH', __DIR__ . '/../../public/assets/uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB

?>
