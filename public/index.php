<?php
// ===============================
// PUBLIC HOME / DASHBOARD (NO LOGIN)
// ===============================

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../includes/header.php";

// Fetch active products
$sql = "
    SELECT id, title, price, image
    FROM products
    WHERE status = 'active'
    ORDER BY created_at DESC
";

$result = mysqli_query($conn, $sql);
?>

<!-- HERO SECTION -->
<section class="home-hero">
    <div class="home-hero-content">
        <h1>Welcome to PureHome</h1>

        <p>
            Discover premium home décor & furniture crafted for modern living.
        </p>

        <div class="home-hero-actions">
            <a href="#products" class="lux-btn">
                Explore Products
            </a>

            <a href="<?= BASE_URL ?>/login.php" class="lux-btn-outline">
                Login
            </a>
        </div>
    </div>
</section>

<!-- PRODUCTS SECTION -->
<section id="products" class="container home-section">

    <h2>Latest Products</h2>


    <div class="product-grid">

        <?php if ($result && mysqli_num_rows($result) > 0): ?>

            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="product-card">

                    <div class="product-image">
                        <?php if (!empty($row['image'])): ?>
                            <img src="<?= htmlspecialchars($row['image']) ?>"
                                 alt="Product">
                        <?php else: ?>
                            <img src="<?= BASE_URL ?>/assets/images/no-image.png"
                                 alt="No Image">
                        <?php endif; ?>
                    </div>

                    <h3><?= htmlspecialchars($row['title']) ?></h3>



                    <p class="price">
                        ₹<?= number_format($row['price'], 2) ?>
                    </p>

                    <a href="<?= BASE_URL ?>/product.php?id=<?= $row['id'] ?>"
                       class="btn">
                        View Product
                    </a>

                </div>
            <?php endwhile; ?>

        <?php else: ?>

            <div class="empty-box">
                <p>No products available right now.</p>
            </div>

        <?php endif; ?>

    </div>

</section>

<?php require_once "../includes/footer.php"; ?>
