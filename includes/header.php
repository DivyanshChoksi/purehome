<?php
require_once __DIR__ . "/../config/config.php";
$user = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>PureHome</title>

    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <header class="main-header">
        <div class="header-container">

            <!-- LEFT : LOGO -->
            <a href="/index.php" class="logo-wrap">
                <img src="/assets/images/logo.png" class="site-logo" alt="PureHome">
                <span class="logo-text">
                    Pure<span class="logo-accent">Home</span>
                </span>
            </a>

            <!-- CENTER : SEARCH -->
            <form action="/search.php" method="GET" class="header-search">
                <input type="text" name="q" placeholder="Search furniture, décor..." required>
                <button type="submit" aria-label="Search">
                    <i class="fa fa-search"></i>
                </button>
            </form>

            <!-- RIGHT : NAV -->
            <nav class="nav-right">

                <!-- ICONS -->
                <a href="/track-order.php" class="icon-btn" title="Track Order">
                    <i class="fa fa-truck"></i>
                </a>

                <a href="/cart.php" class="icon-btn cart-icon">
                    <i class="fa fa-shopping-cart"></i>
                    <span class="cart-badge">0</span>
                </a>

                <!-- USER -->
                <?php if ($user): ?>
                    <div class="user-inline" id="userMenu">
                        <i class="fa fa-user"></i>

                        <div class="user-dropdown" id="userDropdown">
                            <a href="/dashboards/user/dashboard.php">Dashboard</a>
                            <a href="/orders.php">My Orders</a>
                            <a href="/logout.php">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/login.php" class="login-btn">Login</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <script src="/assets/js/app.js"></script>