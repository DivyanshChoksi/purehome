<?php
// ===============================
// CART PAGE (PUBLIC - NO LOGIN)
// ===============================

// Load config & database
require_once "../config/config.php";
require_once "../config/database.php";

// Load header
require_once "../includes/header.php";

// Initialize cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ADD TO CART
if (isset($_GET['add'])) {
    $product_id = (int) $_GET['add'];

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }

    header("Location: cart.php");
    exit;
}

// REMOVE FROM CART
if (isset($_GET['remove'])) {
    $product_id = (int) $_GET['remove'];
    unset($_SESSION['cart'][$product_id]);

    header("Location: cart.php");
    exit;
}

?>

<main class="container">

    <h1>Your Cart</h1>

    <?php if (empty($_SESSION['cart'])): ?>
        <p>Your cart is empty.</p>
        <a href="index.php">Continue Shopping</a>

    <?php else: ?>

        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Action</th>
            </tr>

            <?php
            $grand_total = 0;

            foreach ($_SESSION['cart'] as $id => $qty):
                $product = mysqli_fetch_assoc(
                    mysqli_query($conn, "SELECT title, price FROM products WHERE id = $id")
                );

                if (!$product) continue;

                $total = $product['price'] * $qty;
                $grand_total += $total;
            ?>
            <tr>
                <td><?= htmlspecialchars($product['title']) ?></td>
                <td>₹<?= number_format($product['price'], 2) ?></td>
                <td><?= $qty ?></td>
                <td>₹<?= number_format($total, 2) ?></td>
                <td>
                    <a href="cart.php?remove=<?= $id ?>">Remove</a>
                </td>
            </tr>
            <?php endforeach; ?>

            <tr>
                <th colspan="3" align="right">Grand Total</th>
                <th colspan="2">₹<?= number_format($grand_total, 2) ?></th>
            </tr>
        </table>

        <br>

        <a href="checkout.php" class="btn">Proceed to Checkout</a>

    <?php endif; ?>

</main>

<?php
// Load footer
require_once "../includes/footer.php";
?>
