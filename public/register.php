<?php
// ===============================
// USER REGISTER PAGE (GLASS UI)
// ===============================

require_once "../config/config.php";
require_once "../config/database.php";

// If already logged in → redirect
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$error = "";

// Handle USER registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_user'])) {

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if ($name === "" || $email === "" || $password === "") {
        $error = "All fields are required.";
    } else {

        // Check email
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Email already registered.";
        } else {

            // Encrypt password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert USER
            $query = "
                INSERT INTO users (name, email, password, role)
                VALUES ('$name', '$email', '$hashedPassword', 'user')
            ";

            if (mysqli_query($conn, $query)) {
                header("Location: login.php");
                exit;
            } else {
                $error = "Registration failed. Try again.";
            }
        }
    }
}
?>

<?php require_once "../includes/header.php"; ?>

<section class="auth-page">

    <div class="auth-box">

        <!-- LEFT IMAGE -->
        <div class="auth-image">
            <div class="auth-image-text">
                <h2>Create your<br>Account</h2>
                <p>Join PureHome and explore premium décor.</p>
            </div>
        </div>

        <!-- RIGHT FORM (GLASS) -->
        <div class="auth-form-box">

            <h2>Sign Up</h2>

            <?php if ($error): ?>
                <p style="color:#c0392b; font-size:14px; margin-bottom:15px;">
                    <?= htmlspecialchars($error) ?>
                </p>
            <?php endif; ?>

            <form method="POST" autocomplete="off">

                <input type="text" name="name" placeholder="Full name" required>

                <input type="email" name="email" placeholder="Email address" required>

                <input type="password" name="password" placeholder="Password" required>

                <button type="submit" name="register_user" class="login-btn">
                    Create Account
                </button>

                <p class="auth-switch">
                    Already have an account?
                    <a href="login.php">Login</a>
                </p>

                <p class="auth-switch">
                    Want to sell products?
                    <a href="vendorregister.php">Register as Vendor</a>
                </p>

            </form>

        </div>

    </div>

</section>

<?php require_once "../includes/footer.php"; ?>
