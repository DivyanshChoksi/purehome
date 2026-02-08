<?php
// ===============================
// SINGLE PRODUCT PAGE (PUBLIC)
// ===============================

// Load config & database
require_once "../config/config.php";
require_once "../config/database.php";

// Load header
require_once "../includes/header.php";

// Check product ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p>Invalid product.</p>";
    require_once "../includes/footer.php";
    exit;
}

$product_id = (int) $_GET['id'];

// Fetch product
$sql = "SELECT * FROM products WHERE id = $product_id AND status = 'active'";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "<p>Product not found.</p>";
    require_once "../includes/footer.php";
    exit;
}

$product = mysqli_fetch_assoc($result);
?>

<main class="container">

    <div class="product-details">

        <div class="product-image">
            <?php if (!empty($product['image'])): ?>
                <img src="<?= htmlspecialchars($product['image']) ?>" alt="Product Image">
            <?php else: ?>
                <img src="../assets/images/no-image.png" alt="No Image">
            <?php endif; ?>
        </div>

        <div class="product-info">
            <h1><?= htmlspecialchars($product['title']) ?></h1>

            <p class="price">
                ₹<?= number_format($product['price'], 2) ?>
            </p>

            <p class="description">
                <?= nl2br(htmlspecialchars($product['description'])) ?>
            </p>

            <!-- Cart logic will be added later -->
            <a href="cart.php?add=<?= $product['id'] ?>" class="btn">
                Add to Cart
            </a>

        </div>

    </div>

</main>

<?php
// Load footer
require_once "../includes/footer.php";
?>
