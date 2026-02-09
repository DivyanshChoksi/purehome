<?php
// ===============================
// VENDOR ADD PRODUCT (DASHBOARD)
// ===============================

// BASE PATH
$basePath = dirname(__DIR__, 3);

require_once $basePath . "/config/config.php";
require_once $basePath . "/config/database.php";
require_once $basePath . "/includes/auth.php";
require_once $basePath . "/config/cloudinary.php";

use Cloudinary\Api\Upload\UploadApi;

// -------------------------------
// AUTH CHECK
// -------------------------------
requireLogin();

if ($_SESSION['user']['role'] !== 'vendor') {
    header("Location: /login.php");
    exit;
}

$user_id = (int) $_SESSION['user']['id'];

// -------------------------------
// VENDOR APPROVAL CHECK
// -------------------------------
$stmt = mysqli_prepare(
    $conn,
    "SELECT business_name, status 
     FROM vendor_profiles 
     WHERE user_id = ? 
     LIMIT 1"
);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$vendor = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$vendor) {
    header("Location: complete-profile.php");
    exit;
}

if ($vendor['status'] !== 'approved') {
    header("Location: /vendor-pending.php");
    exit;
}

// -------------------------------
// FORM LOGIC
// -------------------------------
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title       = trim($_POST['title']);
    $price       = trim($_POST['price']);
    $description = trim($_POST['description']);
    $imageUrl    = "";

    if ($title === "" || $price === "") {
        $error = "Product title and price are required.";
    } else {

        // IMAGE UPLOAD (CLOUDINARY FIRST)
        if (!empty($_FILES['image']['name'])) {
            try {
                $upload = (new UploadApi())->upload(
                    $_FILES['image']['tmp_name'],
                    [
                        "folder" => "purehome/products",
                        "resource_type" => "image"
                    ]
                );
                $imageUrl = $upload['secure_url'];
            } catch (Exception $e) {

                // LOCAL FALLBACK
                $uploadDir = $basePath . "/uploads/products/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = time() . "_" . basename($_FILES["image"]["name"]);
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $uploadDir . $fileName)) {
                    $imageUrl = "uploads/products/" . $fileName;
                }
            }
        }

        // INSERT PRODUCT
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO products 
            (vendor_id, title, price, image, description, status)
            VALUES (?, ?, ?, ?, ?, 'active')"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "isdss",
            $user_id,
            $title,
            $price,
            $imageUrl,
            $description
        );

        if (mysqli_stmt_execute($stmt)) {
            $success = "Product added successfully.";
        } else {
            $error = "Failed to add product.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product | Vendor Dashboard</title>

    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="vendor-page">

<div class="vendor-layout">

    <!-- SIDEBAR -->
    <aside class="vendor-sidebar">
        <div class="brand">Pure<span>Home</span></div>

        <nav class="vendor-nav">
            <a href="dashboard.php">
                <i class="fa fa-chart-pie"></i> Dashboard
            </a>
            <a href="products.php">
                <i class="fa fa-box"></i> Products
            </a>
            <a class="active">
                <i class="fa fa-plus"></i> Add Product
            </a>
            <a href="/logout.php">
                <i class="fa fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </aside>

    <!-- MAIN -->
    <main class="vendor-main">

        <!-- TOP BAR -->
        <header class="vendor-topbar">
            <div>
                <h2>Add Product</h2>
                <span class="muted">Create a new product</span>
            </div>

            <div class="vendor-profile">

                <!-- THEME TOGGLE (SAME AS ADMIN + DASHBOARD) -->
                <button id="themeToggle" title="Toggle theme">
                    <i class="fa fa-sun" id="themeIcon"></i>
                </button>

                <i class="fa fa-user-circle"></i> Vendor
            </div>
        </header>

        <!-- FORM CARD -->
        <section class="vendor-section add-product-form">

            <?php if ($error): ?>
                <div class="alert error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="grid-form">

                <div class="form-group">
                    <label>Product Title</label>
                    <input type="text" name="title" required>
                </div>

                <div class="form-group">
                    <label>Price (₹)</label>
                    <input type="number" step="0.01" name="price" required>
                </div>

                <div class="form-group full">
                    <label>Description</label>
                    <textarea name="description"></textarea>
                </div>

                <div class="form-group full">
                    <label>Product Image</label>
                    <input type="file" name="image" accept="image/*">
                </div>

                <div class="form-group full">
                    <button type="submit" class="btn-primary">
                        <i class="fa fa-save"></i> Add Product
                    </button>
                </div>

            </form>

        </section>

    </main>
</div>

<!-- GLOBAL JS (REQUIRED FOR THEME + UI) -->
<script src="/assets/js/app.js"></script>

</body>
</html>
