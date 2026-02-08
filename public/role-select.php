<?php
// ===============================
// ROLE SELECTION PAGE
// ===============================

require_once "../config/config.php";
require_once "../includes/header.php";
?>

<main class="container">

    <h1>Create Your Account</h1>
    <p>Select how you want to use PureHome</p>

    <div class="role-box">

        <div class="role-card">
            <h2>User</h2>
            <p>Buy products and place orders</p>
            <a href="register.php" class="btn">
                Continue as User
            </a>
        </div>

        <div class="role-card">
            <h2>Vendor</h2>
            <p>Sell products and manage your store</p>
            <a href="vendorregister.php" class="btn">
                Continue as Vendor
            </a>
        </div>

    </div>

</main>

<?php require_once "../includes/footer.php"; ?>
