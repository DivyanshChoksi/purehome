<?php
// ===============================
// CHECKOUT PAGE (FINAL FIXED)
// ===============================

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../includes/auth.php";

// Require logged-in USER only
requireUser();

// Load header
require_once "../includes/header.php";

// Validate cart
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    require_once "../includes/footer.php";
    exit;
}

$user_id = (int) $_SESSION['user']['id'];
$cart = $_SESSION['cart'];
$total_amount = 0;

// ===============================
// CALCULATE TOTAL (SAFE)
// ===============================
foreach ($cart as $product_id => $qty) {

    $product_id = (int) $product_id;
    $qty = (int) $qty;

    $result = mysqli_query(
        $conn,
        "SELECT price FROM products WHERE id = $product_id AND status='active'"
    );

    if ($row = mysqli_fetch_assoc($result)) {
        $total_amount += $row['price'] * $qty;
    }
}

// ===============================
// PLACE ORDER
// ===============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {

    mysqli_begin_transaction($conn);

    try {

        // Insert order
        $orderQuery = "
            INSERT INTO orders (user_id, total_amount, payment_status)
            VALUES ($user_id, $total_amount, 'pending')
        ";

        if (!mysqli_query($conn, $orderQuery)) {
            throw new Exception("Order insert failed");
        }

        $order_id = mysqli_insert_id($conn);

        // Insert order items
        foreach ($cart as $product_id => $qty) {

            $product_id = (int) $product_id;
            $qty = (int) $qty;

            $productResult = mysqli_query(
                $conn,
                "SELECT price, vendor_id FROM products WHERE id = $product_id"
            );

            if (!$product = mysqli_fetch_assoc($productResult)) {
                continue;
            }

            $price = $product['price'];
            $vendor_id = (int) $product['vendor_id'];

            $itemQuery = "
                INSERT INTO order_items
                (order_id, product_id, vendor_id, price, quantity)
                VALUES
                ($order_id, $product_id, $vendor_id, $price, $qty)
            ";

            if (!mysqli_query($conn, $itemQuery)) {
                throw new Exception("Order item insert failed");
            }
        }

        // Commit transaction
        mysqli_commit($conn);

        // Clear cart
        unset($_SESSION['cart']);

        // Redirect
        header("Location: " . BASE_URL . "/dashboards/user/dashboard.php");
        exit;

    } catch (Exception $e) {

        mysqli_rollback($conn);

        echo "<p class='error'>❌ Order failed. Please try again.</p>";
    }
}
?>

<main class="container">

    <h1>Checkout</h1>

    <p>
        <strong>Total Amount:</strong>
        ₹<?= number_format($total_amount, 2) ?>
    </p>

    <form method="POST">
        <p>
            Payment Method:
            <strong>Cash / Pending</strong>
        </p>

        <button type="submit"
                name="place_order"
                class="btn">
            Place Order
        </button>
    </form>

</main>

<?php require_once "../includes/footer.php"; ?>
