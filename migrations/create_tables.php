<?php
// =====================================
// PUREHOME DATABASE AUTO SETUP
// Run: php migrations/create_tables.php
// =====================================

require_once __DIR__ . "/../config/database.php";

echo "Starting PureHome database setup...\n";

function runQuery($conn, $sql, $name)
{
    if (mysqli_query($conn, $sql)) {
        echo "✔ $name table created\n";
    } else {
        echo "✖ $name error: " . mysqli_error($conn) . "\n";
    }
}

/* USERS */
runQuery($conn, "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(150) UNIQUE,
    password VARCHAR(255),
    role ENUM('user','vendor','admin'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)", "users");

/* VENDOR PROFILES */
runQuery($conn, "
CREATE TABLE IF NOT EXISTS vendor_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE,
    business_name VARCHAR(150),
    business_type VARCHAR(100),
    gst_number VARCHAR(20),
    bank_name VARCHAR(100),
    account_holder_name VARCHAR(150),
    account_number VARCHAR(30),
    ifsc_code VARCHAR(20),
    business_address TEXT,
    gst_certificate_pdf VARCHAR(255),
    business_photo VARCHAR(255),
    owner_id_proof_pdf VARCHAR(255),
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)", "vendor_profiles");

/* PRODUCTS */
runQuery($conn, "
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vendor_id INT,
    title VARCHAR(150),
    price DECIMAL(10,2),
    image VARCHAR(255),
    description TEXT,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vendor_id) REFERENCES users(id) ON DELETE CASCADE
)", "products");

/* ORDERS */
runQuery($conn, "
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_amount DECIMAL(10,2),
    payment_status ENUM('pending','paid','failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)", "orders");

/* ORDER ITEMS */
runQuery($conn, "
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    vendor_id INT,
    price DECIMAL(10,2),
    quantity INT,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (vendor_id) REFERENCES users(id) ON DELETE CASCADE
)", "order_items");

echo "Database setup completed successfully.\n";
