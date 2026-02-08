<?php
// ===============================
// LOGOUT PAGE
// ===============================

require_once "../config/config.php";

// Unset all session variables
$_SESSION = [];

// Destroy session
session_destroy();

// Redirect to public dashboard
header("Location: index.php");
exit;
