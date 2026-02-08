<?php
// ===============================
// GLOBAL CONFIG
// ===============================

// Start session safely (once)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
 | SERVER MODE:
 | php -S localhost:8000 -t public
 | public/ is the web root
 |
 | BASE_URL should be EMPTY string
 | so links become: /login.php, /assets/...
 */
define('BASE_URL', '');

// Set timezone
date_default_timezone_set('Asia/Kolkata');

// ===============================
// DEVELOPMENT MODE
// ===============================
error_reporting(E_ALL);
ini_set('display_errors', 1);
