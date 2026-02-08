<?php
// ===============================
// AUTHENTICATION HELPERS (FINAL)
// ===============================

// Make sure config is loaded (session + BASE_URL)
require_once __DIR__ . "/../config/config.php";

/**
 * Require user to be logged in
 */
function requireLogin()
{
    if (!isset($_SESSION['user'])) {
        header("Location: " . BASE_URL . "/login.php");
        exit;
    }
}

/**
 * Check login status
 */
function isLoggedIn()
{
    return isset($_SESSION['user']);
}

/**
 * Get current logged-in user
 */
function getCurrentUser()
{
    return $_SESSION['user'] ?? null;
}

/**
 * Require ADMIN role
 */
function requireAdmin() {
    if (
        !isset($_SESSION['user']) ||
        $_SESSION['user']['role'] !== 'admin'
    ) {
        header("Location: /login.php");
        exit;
    }
}

/**
 * Require VENDOR role
 */
function requireVendor()
{
    requireLogin();

    if ($_SESSION['user']['role'] !== 'vendor') {
        header("Location: " . BASE_URL . "/index.php");
        exit;
    }
}

/**
 * Require USER role
 */
function requireUser()
{
    requireLogin();

    if ($_SESSION['user']['role'] !== 'user') {
        header("Location: " . BASE_URL . "/index.php");
        exit;
    }
}
