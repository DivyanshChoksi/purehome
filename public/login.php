<?php
// ===============================
// LOGIN PAGE (FINAL & CORRECT)
// ===============================

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../config/database.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = "Email and password are required.";
    } else {

        // Fetch user
        $stmt = mysqli_prepare(
            $conn,
            "SELECT id, name, password, role FROM users WHERE email = ? LIMIT 1"
        );
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {

            if (password_verify($password, $user['password'])) {

                // Save session
                $_SESSION['user'] = [
                    'id'   => $user['id'],
                    'name' => $user['name'],
                    'role' => $user['role']
                ];

                /* ===============================
                   ROLE BASED REDIRECT
                =============================== */

                // ADMIN
                if ($user['role'] === 'admin') {
                    header("Location: /dashboards/admin/dashboard.php");
                    exit;
                }

                // VENDOR (check approval)
                if ($user['role'] === 'vendor') {

                    $uid = $user['id'];
                    $v = mysqli_query(
                        $conn,
                        "SELECT status FROM vendor_profiles WHERE user_id = $uid LIMIT 1"
                    );
                    $vendor = mysqli_fetch_assoc($v);

                    if ($vendor && $vendor['status'] === 'approved') {
                        header("Location: /dashboards/vendor/dashboard.php");
                        exit;
                    } else {
                        header("Location: /vendor-pending.php");
                        exit;
                    }
                }

                // NORMAL USER
                header("Location: /dashboards/user/dashboard.php");
                exit;

            } else {
                $error = "Invalid email or password.";
            }

        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | PureHome</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>

<section class="auth-page">
    <div class="auth-box">

        <!-- LEFT -->
        <div class="auth-image">
            <div class="auth-image-text">
                <h2>Welcome Back</h2>
                <p>Login to manage your account</p>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="auth-form-box">
            <h2>Login</h2>

            <?php if ($error): ?>
                <p style="color:#c0392b; margin-bottom:15px;">
                    <?= htmlspecialchars($error) ?>
                </p>
            <?php endif; ?>

            <form method="POST" autocomplete="off">

                <input type="email" name="email" placeholder="Email address" required>
                <input type="password" name="password" placeholder="Password" required>

                <button type="submit" class="login-btn">Login</button>

                <p class="auth-switch">
                    Don’t have an account?
                    <a href="/register.php">Sign up</a>
                </p>

            </form>
        </div>

    </div>
</section>

</body>
</html>
