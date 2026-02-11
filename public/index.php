<?php
require_once "../config/config.php";
require_once "../config/database.php";
require_once "../includes/header.php";

/* ===============================
   FETCH LATEST ACTIVE PRODUCTS
=============================== */

$stmt = mysqli_prepare(
    $conn,
    "SELECT id, title, price, image
     FROM products
     WHERE status = 'active'
     ORDER BY created_at DESC
     LIMIT 8"
);

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!-- HERO -->
<section class="home-hero">
    <div class="home-hero-content">
        <h1>Welcome to PureHome</h1>
        <p>Discover premium home décor & furniture crafted for modern living.</p>

        <div class="home-hero-actions">
            <a href="#products" class="lux-btn">Explore Products</a>
            <a href="<?= BASE_URL ?>/login.php" class="lux-btn-outline">Login</a>
        </div>
    </div>
</section>

<!-- PRODUCTS -->
<section id="products" class="home-products container">

    <h2 class="section-title">Latest Products</h2>

    <div class="product-grid">

        <?php if ($result && mysqli_num_rows($result) > 0): ?>

            <?php while ($row = mysqli_fetch_assoc($result)): ?>

                <?php
                // ✅ SIMPLE IMAGE LOGIC (Cloudinary Compatible)
                $image = !empty($row['image'])
                    ? $row['image']
                    : BASE_URL . "/assets/images/no-image.png";
                ?>

                <div class="product-card light-card">

                    <div class="product-img">
                        <img
                            src="<?= htmlspecialchars($image) ?>"
                            alt="<?= htmlspecialchars($row['title']) ?>"
                            loading="lazy"
                        >
                    </div>

                    <h3><?= htmlspecialchars($row['title']) ?></h3>

                    <p class="price">
                        ₹<?= number_format((float)$row['price'], 2) ?>
                    </p>

                    <a
                        href="<?= BASE_URL ?>/product.php?id=<?= (int)$row['id'] ?>"
                        class="btn-primary"
                    >
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
