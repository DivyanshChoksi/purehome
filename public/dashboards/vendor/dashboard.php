<?php
// ===============================
// VENDOR DASHBOARD
// ===============================

require_once __DIR__ . "/../../../config/config.php";
require_once __DIR__ . "/../../../config/database.php";
require_once __DIR__ . "/../../../includes/auth.php";

// AUTH
requireLogin();

if ($_SESSION['user']['role'] !== 'vendor') {
    header("Location: /login.php");
    exit;
}

$vendorId = (int) $_SESSION['user']['id'];

// ===============================
// VENDOR PROFILE
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
$vendor = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$vendor) {
    header("Location: complete-profile.php");
    exit;
}

if ($vendor['status'] !== 'approved') {
    header("Location: /vendor-pending.php");
    exit;
}

// ===============================
// STATS
// ===============================

// TOTAL ORDERS
$qOrders = mysqli_query(
    $conn,
    "SELECT COUNT(DISTINCT o.id) total
     FROM orders o
     JOIN order_items oi ON oi.order_id = o.id
     JOIN products p ON p.id = oi.product_id
     WHERE p.vendor_id = $vendorId"
);
$totalOrders = mysqli_fetch_assoc($qOrders)['total'] ?? 0;

// TOTAL CUSTOMERS
$qCustomers = mysqli_query(
    $conn,
    "SELECT COUNT(DISTINCT o.user_id) total
     FROM orders o
     JOIN order_items oi ON oi.order_id = o.id
     JOIN products p ON p.id = oi.product_id
     WHERE p.vendor_id = $vendorId"
);
$totalCustomers = mysqli_fetch_assoc($qCustomers)['total'] ?? 0;

// TOTAL REVENUE
$qRevenue = mysqli_query(
    $conn,
    "SELECT COALESCE(SUM(oi.price * oi.quantity),0) total
     FROM order_items oi
     JOIN products p ON p.id = oi.product_id
     WHERE p.vendor_id = $vendorId"
);
$totalRevenue = mysqli_fetch_assoc($qRevenue)['total'] ?? 0;

// PENDING ORDERS
$pendingOrders = $totalOrders;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vendor Dashboard | PureHome</title>

    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="vendor-page">

<div class="vendor-layout">

    <!-- SIDEBAR -->
    <aside class="vendor-sidebar">
        <div class="brand">Pure<span>Home</span></div>

        <nav class="vendor-nav">
            <a class="active">
                <i class="fa fa-chart-pie"></i> Dashboard
            </a>
            <a href="products.php">
                <i class="fa fa-box"></i> Products
            </a>
            <a href="add-product.php">
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
                <h2>Hello, <?= htmlspecialchars($vendor['business_name']) ?> 👋</h2>
                <span class="muted">This month overview</span>
            </div>

            <div class="vendor-profile">

                <!-- THEME TOGGLE (SAME AS ADMIN) -->
                <button id="themeToggle" title="Toggle theme">
                    <i class="fa fa-sun" id="themeIcon"></i>
                </button>

                <i class="fa fa-user-circle"></i> Vendor
            </div>
        </header>

        <!-- STATS -->
        <section class="vendor-stats">

            <div class="stat-card">
                <span>Total Revenue</span>
                <p>₹ <?= number_format($totalRevenue) ?></p>
            </div>

            <div class="stat-card">
                <span>Total Orders</span>
                <p><?= $totalOrders ?></p>
            </div>

            <div class="stat-card">
                <span>Customers</span>
                <p><?= $totalCustomers ?></p>
            </div>

            <div class="stat-card highlight">
                <span>Pending Orders</span>
                <p><?= $pendingOrders ?></p>
            </div>

        </section>

        <!-- CHART -->
        <section class="vendor-section">
            <h3>Revenue Overview</h3>
            <canvas id="revenueChart" height="120"></canvas>
        </section>

    </main>
</div>

<script>
new Chart(document.getElementById("revenueChart"), {
    type: "bar",
    data: {
        labels: ["Jan","Feb","Mar","Apr","May","Jun"],
        datasets: [{
            data: [0,0,0,0,0,0],
            backgroundColor: "#4f7cff",
            borderRadius: 6
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>

<!-- GLOBAL JS -->
<script src="/assets/js/app.js"></script>

</body>
</html>
