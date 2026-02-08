<?php
require_once "../config/config.php";

/* Optional protection */
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'vendor') {
    header("Location: /login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Approval Pending | PureHome</title>

    <!-- LOAD SAME CSS AS LOGIN -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>

<section class="auth-page">

    <div class="auth-box">

        <!-- LEFT IMAGE -->
        <div class="auth-image"
             style="background-image:url('/assets/images/interior-bg.jpg')">
            <div class="auth-image-text">
                <h2>Approval Pending ⏳</h2>
                <p>
                    Your vendor account is under review.<br>
                    Please wait for admin approval.
                </p>
            </div>
        </div>

        <!-- RIGHT CONTENT -->
        <div class="auth-form-box">

            <h2>Almost There!</h2>

            <p style="margin: 15px 0; line-height: 1.6;">
                Thank you for registering as a vendor on <strong>PureHome</strong>.
                <br><br>
                Once your account is approved by the admin, you will be able to:
            </p>

            <ul style="margin-left: 18px; margin-bottom: 20px;">
                <li>Add and manage products</li>
                <li>View orders</li>
                <li>Access vendor dashboard</li>
            </ul>

            <a href="/logout.php" class="login-btn">
                Logout
            </a>

        </div>

    </div>

</section>

</body>
</html>
