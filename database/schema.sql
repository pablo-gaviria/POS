-- POS System Database Schema

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'staff', 'cashier') DEFAULT 'staff',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
);

-- Categories Table
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_name (name)
);

-- Products Table
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sku VARCHAR(100) UNIQUE NOT NULL,
    barcode INT UNIQUE DEFAULT NULL,
    name VARCHAR(255) NOT NULL,
    image VARCHAR(255),
    description TEXT,
    category VARCHAR(100),
    price DECIMAL(10, 2) NOT NULL,
    cost DECIMAL(10, 2) NOT NULL,
    quantity INT DEFAULT 0,
    reorder_level INT DEFAULT 10,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    INDEX idx_sku (sku),
    INDEX idx_barcode (barcode),
    INDEX idx_category (category),
    INDEX idx_quantity (quantity)
);

-- Sales Table
CREATE TABLE IF NOT EXISTS sales (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cashier_id INT,
    total_amount DECIMAL(12, 2) NOT NULL,
    payment_method VARCHAR(50),
    status ENUM('completed', 'cancelled') DEFAULT 'completed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cashier_id) REFERENCES users(id),
    INDEX idx_cashier (cashier_id),
    INDEX idx_date (created_at),
    INDEX idx_status (status)
);

-- Sale Items Table
CREATE TABLE IF NOT EXISTS sale_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sale_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(12, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id),
    INDEX idx_sale (sale_id),
    INDEX idx_product (product_id)
);

-- Inventory Movements Table
CREATE TABLE IF NOT EXISTS inventory_movements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    quantity_change INT NOT NULL,
    reason VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    INDEX idx_product (product_id),
    INDEX idx_date (created_at)
);

-- Demo Data
INSERT INTO users (name, email, password, role, status) VALUES
('Admin User', 'admin@pos.com', '$2y$10$k1f1iWzYc88mYPXLHxLUXO5f1jVvH8fE8Q4UzR8qJPZ8n4M2j5jly', 'admin', 'active'),
('Staff User', 'staff@pos.com', '$2y$10$k1f1iWzYc88mYPXLHxLUXO5f1jVvH8fE8Q4UzR8qJPZ8n4M2j5jly', 'staff', 'active'),
('Cashier User', 'cashier@pos.com', '$2y$10$k1f1iWzYc88mYPXLHxLUXO5f1jVvH8fE8Q4UzR8qJPZ8n4M2j5jly', 'cashier', 'active');

INSERT INTO categories (name, description) VALUES
('Beverages', 'Drinks and beverages'),
('Snacks', 'Snack items and chips'),
('Dairy', 'Dairy products and milk'),
('Bakery', 'Bread and bakery items'),
('Household', 'Household items and supplies');

INSERT INTO products (sku, barcode, name, description, category, price, cost, quantity, reorder_level) VALUES
('SKU001', 100001, 'Coca Cola 330ml', 'Soft Drink', 'Beverages', 1.50, 0.80, 50, 10),
('SKU002', 100002, 'Pepsi 500ml', 'Soft Drink', 'Beverages', 2.00, 1.00, 30, 10),
('SKU003', 100003, 'Chips Pack', 'Potato Chips', 'Snacks', 1.25, 0.60, 45, 15),
('SKU004', 100004, 'Chocolate Bar', 'Sweet Chocolate', 'Snacks', 1.50, 0.75, 8, 10),
('SKU005', 100005, 'Milk 1L', 'Fresh Milk', 'Dairy', 2.50, 1.20, 5, 10),
('SKU006', 100006, 'Bread Loaf', 'White Bread', 'Bakery', 2.00, 0.80, 20, 10),
('SKU007', 100007, 'Butter 250g', 'Dairy Butter', 'Dairy', 4.50, 2.00, 12, 5),
('SKU008', 100008, 'Orange Juice 1L', 'Fresh Orange Juice', 'Beverages', 3.50, 1.50, 15, 8),
('SKU009', 100009, 'Mineral Water 2L', 'Drinking Water', 'Beverages', 1.00, 0.35, 100, 20),
('SKU010', 100010, 'Coffee 500g', 'Ground Coffee', 'Household', 5.00, 2.50, 8, 5);

-- Password for all demo users: 'password' (hashed with bcrypt)
