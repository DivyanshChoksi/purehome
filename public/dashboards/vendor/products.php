<?php
// ======================================
// VENDOR PRODUCTS PAGE (FINAL CLEAN)
// ======================================

// BASE PATH
$basePath = dirname(__DIR__, 3);

require_once $basePath . "/config/config.php";
require_once $basePath . "/config/database.php";
require_once $basePath . "/includes/auth.php";

// ===============================
// AUTH CHECK
// ===============================
requireLogin();

if ($_SESSION['user']['role'] !== 'vendor') {
    header("Location: " . BASE_URL . "/login.php");
    exit;
}

$vendorId = (int) $_SESSION['user']['id'];

// ===============================
// CHECK VENDOR STATUS
// ===============================
$stmt = mysqli_prepare(
    $conn,
    "SELECT business_name, status
     FROM vendor_profiles
     WHERE user_id = ?
     LIMIT 1"
);

mysqli_stmt_bind_param($stmt, "i", $vendorId);
mysqli_stmt_execute($stmt);
$resultVendor = mysqli_stmt_get_result($stmt);
$vendor = mysqli_fetch_assoc($resultVendor);

if (!$vendor) {
    header("Location: complete-profile.php");
    exit;
}

if ($vendor['status'] !== 'approved') {
    header("Location: " . BASE_URL . "/vendor-pending.php");
    exit;
}

// ===============================
// DELETE PRODUCT (SECURE)
// ===============================
if (isset($_GET['delete'])) {

    $productId = (int) $_GET['delete'];

    $deleteStmt = mysqli_prepare(
        $conn,
        "DELETE FROM products WHERE id = ? AND vendor_id = ?"
    );

    mysqli_stmt_bind_param($deleteStmt, "ii", $productId, $vendorId);
    mysqli_stmt_execute($deleteStmt);

    header("Location: products.php");
    exit;
}

// ===============================
// FETCH PRODUCTS
// ===============================
$productStmt = mysqli_prepare(
    $conn,
    "SELECT id, title, price, image
     FROM products
     WHERE vendor_id = ?
     ORDER BY id DESC"
);

mysqli_stmt_bind_param($productStmt, "i", $vendorId);
mysqli_stmt_execute($productStmt);
$result = mysqli_stmt_get_result($productStmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Products | Vendor Dashboard</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
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
            <a class="active">
                <i class="fa fa-box"></i> Products
            </a>
            <a href="add-product.php">
                <i class="fa fa-plus"></i> Add Product
            </a>
            <a href="<?= BASE_URL ?>/logout.php">
                <i class="fa fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </aside>

    <!-- MAIN -->
    <main class="vendor-main">

        <!-- TOP BAR -->
        <header class="vendor-topbar">
            <div>
                <h2>My Products</h2>
                <span class="muted">Manage your products</span>
            </div>

            <div class="vendor-profile">

                <!-- THEME TOGGLE -->
                <button id="themeToggle" title="Toggle theme">
                    <i id="themeIcon" class="fa fa-sun"></i>
                </button>

                <a href="add-product.php" class="btn-primary">
                    <i class="fa fa-plus"></i> Add Product
                </a>
            </div>
        </header>

        <!-- PRODUCTS SECTION -->
        <section class="vendor-section">

            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <div class="product-grid">

                    <?php while ($row = mysqli_fetch_assoc($result)): ?>

                        <?php
                        // ===============================
                        // SMART IMAGE HANDLING
                        // ===============================

                        if (!empty($row['image'])) {

                            if (strpos($row['image'], 'http') === 0) {
                                // Cloudinary image
                                $imageUrl = $row['image'];
                            } else {
                                // Local upload image
                                $imageUrl = BASE_URL . '/' . ltrim($row['image'], '/');
                            }

                        } else {
                            // Default fallback image
                            $imageUrl = BASE_URL . '/assets/images/no-image.png';
                        }
                        ?>

                        <div class="product-card">

                            <div class="product-img">
                                <img
                                    src="<?= htmlspecialchars($imageUrl) ?>"
                                    alt="<?= htmlspecialchars($row['title']) ?>"
                                    loading="lazy"
                                >
                            </div>

                            <h4><?= htmlspecialchars($row['title']) ?></h4>

                            <p class="price">
                                ₹<?= number_format((float)$row['price'], 2) ?>
                            </p>

                            <div class="product-actions">
                                <a href="products.php?delete=<?= (int)$row['id'] ?>"
                                   class="delete-btn"
                                   data-confirm="Delete this product?">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>

                        </div>

                    <?php endwhile; ?>

                </div>

            <?php else: ?>

                <p class="muted">No products added yet.</p>

            <?php endif; ?>

        </section>

    </main>
</div>

<script src="<?= BASE_URL ?>/assets/js/app.js"></script>

</body>
</html>
