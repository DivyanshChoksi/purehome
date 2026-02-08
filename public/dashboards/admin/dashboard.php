<?php
require_once __DIR__ . "/../../../config/config.php";
require_once __DIR__ . "/../../../config/database.php";
require_once __DIR__ . "/../../../includes/auth.php";

requireAdmin();
$admin = $_SESSION['user'];

/* ===============================
   APPROVE / REJECT ACTIONS
=============================== */
if (isset($_GET['approve'])) {
    $id = (int) $_GET['approve'];
    mysqli_query($conn,
        "UPDATE vendor_profiles SET status='approved' WHERE user_id=$id"
    );
    header("Location: dashboard.php");
    exit;
}

if (isset($_GET['reject'])) {
    $id = (int) $_GET['reject'];
    mysqli_query($conn,
        "UPDATE vendor_profiles SET status='rejected' WHERE user_id=$id"
    );
    header("Location: dashboard.php");
    exit;
}

/* ===============================
   DASHBOARD DATA
=============================== */
$totalVendors = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM vendor_profiles")
)['total'];

$totalProducts = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM products")
)['total'];

$pendingCount = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM vendor_profiles WHERE status='pending'")
)['total'];

$blockedCount = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM vendor_profiles WHERE status='rejected'")
)['total'];

$pendingVendors = mysqli_query($conn,
    "SELECT vp.user_id, vp.business_name, vp.business_type, vp.created_at, u.email
     FROM vendor_profiles vp
     JOIN users u ON u.id = vp.user_id
     WHERE vp.status='pending'
     ORDER BY vp.created_at DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | PureHome</title>

    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="admin-page">

<div class="admin-layout">

    <!-- SIDEBAR -->
    <aside class="admin-sidebar">
        <div class="brand">Pure<span>Home</span></div>

        <nav class="admin-nav">
            <a class="active"><i class="fa fa-chart-line"></i> Dashboard</a>
            <a href="/dashboards/admin/vendors.php"><i class="fa fa-store"></i> Vendors</a>
            <a href="/dashboards/admin/products.php"><i class="fa fa-box"></i> Products</a>
            <a href="/dashboards/admin/users.php"><i class="fa fa-users"></i> Users</a>
            <a href="/logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>

    <!-- MAIN -->
    <main class="admin-main">

        <!-- TOPBAR -->
        <header class="admin-topbar">
            <input type="text" placeholder="Search users, vendors, products…">

            <div class="topbar-actions">
                <!-- THEME TOGGLE -->
                <button id="themeToggle" title="Toggle theme">
                    <i class="fa fa-sun" id="themeIcon"></i>
                </button>

                <div class="admin-profile">
                    <i class="fa fa-user-circle"></i>
                    <?= htmlspecialchars($admin['name']) ?>
                </div>
            </div>
        </header>

        <!-- STATS -->
        <section class="admin-stats">
            <div class="stat-card">
                <span>Total Vendors</span>
                <p><?= $totalVendors ?></p>
            </div>
            <div class="stat-card">
                <span>Total Products</span>
                <p><?= $totalProducts ?></p>
            </div>
            <div class="stat-card">
                <span>Pending Approvals</span>
                <p><?= $pendingCount ?></p>
            </div>
            <div class="stat-card blocked">
                <span>Blocked Vendors</span>
                <p><?= $blockedCount ?></p>
            </div>
        </section>

        <!-- TABLE -->
        <section class="admin-section">
            <div class="section-header">
                <h3>Pending Vendor Approvals</h3>
            </div>

            <?php if (mysqli_num_rows($pendingVendors) === 0): ?>
                <p class="muted">No pending vendor approvals 🎉</p>
            <?php else: ?>

            <table class="admin-table">
                <thead>
                <tr>
                    <th>Business</th>
                    <th>Type</th>
                    <th>Email</th>
                    <th>Applied</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                <?php while ($v = mysqli_fetch_assoc($pendingVendors)): ?>
                <tr>
                    <td><?= htmlspecialchars($v['business_name']) ?></td>
                    <td><?= htmlspecialchars($v['business_type']) ?></td>
                    <td><?= htmlspecialchars($v['email']) ?></td>
                    <td><?= date("d M Y", strtotime($v['created_at'])) ?></td>
                    <td>
                        <a href="?approve=<?= $v['user_id'] ?>" class="btn-approve"
                           onclick="return confirm('Approve this vendor?')">
                            Approve
                        </a>
                        <a href="?reject=<?= $v['user_id'] ?>" class="btn-reject"
                           onclick="return confirm('Reject this vendor?')">
                            Reject
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>

                </tbody>
            </table>
            <?php endif; ?>
        </section>

    </main>
</div>

<!-- GLOBAL JS -->
<script src="/assets/js/app.js"></script>
</body>
</html>
