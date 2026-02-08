<?php
// ===============================
// VENDOR REGISTER PAGE (GLASS UI)
// ===============================

require_once "../config/config.php";
require_once "../config/database.php";

// If already logged in → redirect
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$error = "";
$success = "";

// Handle vendor registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_vendor'])) {

    // USER DETAILS
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // BUSINESS DETAILS
    $business_name = trim($_POST['business_name'] ?? '');
    $business_type = trim($_POST['business_type'] ?? '');
    $gst_number    = trim($_POST['gst_number'] ?? '');
    $business_addr = trim($_POST['business_address'] ?? '');

    // BANK DETAILS
    $bank_name      = trim($_POST['bank_name'] ?? '');
    $account_name   = trim($_POST['account_holder_name'] ?? '');
    $account_number = trim($_POST['account_number'] ?? '');
    $ifsc_code      = trim($_POST['ifsc_code'] ?? '');

    // Basic validation
    if (
        $name === "" || $email === "" || $password === "" ||
        $business_name === "" || $bank_name === "" ||
        $account_name === "" || $account_number === "" || $ifsc_code === ""
    ) {
        $error = "All required fields must be filled.";
    } else {

        // Check email
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Email already registered.";
        } else {

            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $userQuery = "
                INSERT INTO users (name, email, password, role)
                VALUES ('$name', '$email', '$hashedPassword', 'vendor')
            ";

            if (mysqli_query($conn, $userQuery)) {

                $user_id = mysqli_insert_id($conn);

                // Insert vendor profile
                $vendorQuery = "
                    INSERT INTO vendor_profiles (
                        user_id,
                        business_name,
                        business_type,
                        gst_number,
                        bank_name,
                        account_holder_name,
                        account_number,
                        ifsc_code,
                        business_address,
                        status
                    ) VALUES (
                        $user_id,
                        '$business_name',
                        '$business_type',
                        '$gst_number',
                        '$bank_name',
                        '$account_name',
                        '$account_number',
                        '$ifsc_code',
                        '$business_addr',
                        'pending'
                    )
                ";

                if (mysqli_query($conn, $vendorQuery)) {
                    $success = "Vendor registered successfully. Please login.";
                } else {
                    $error = "Vendor profile creation failed.";
                }
            } else {
                $error = "Vendor registration failed.";
            }
        }
    }
}
?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">

<section class="vendor-auth">

    <div class="auth-box">

        <!-- LEFT IMAGE -->
        <div class="auth-image" style="background-image:url('<?= BASE_URL ?>/assets/images/interior-bg.jpg')">
    <div class="auth-image-text">
        <h2>Become a<br>Vendor</h2>
        <p>Sell premium products on PureHome.</p>
    </div>
</div>



        <!-- RIGHT FORM -->
        <div class="vendor-form">

            <h2>Vendor Registration</h2>

            <?php if ($error): ?>
                <p class="auth-error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <?php if ($success): ?>
                <p class="auth-success"><?= htmlspecialchars($success) ?></p>
            <?php endif; ?>

            <form method="POST" autocomplete="off">

                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>

                <input type="text" name="business_name" placeholder="Business Name" required>
                <input type="text" name="business_type" placeholder="Business Type">
                <input type="text" name="gst_number" placeholder="GST Number">
                <input type="text" name="business_address" placeholder="Business Address">

                <input type="text" name="bank_name" placeholder="Bank Name" required>
                <input type="text" name="account_holder_name" placeholder="Account Holder Name" required>
                <input type="text" name="account_number" placeholder="Account Number" required>
                <input type="text" name="ifsc_code" placeholder="IFSC Code" required>

                <button type="submit" name="register_vendor" class="login-btn">
                    Register as Vendor
                </button>

                <p class="auth-switch">
                    Already have an account?
                    <a href="login.php">Login</a>
                </p>

            </form>

        </div>

    </div>

</section>
