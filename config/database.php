<?php
// ===============================
// DATABASE CONNECTION FILE
// ===============================

// Database credentials
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "purehome";

// Create connection
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
