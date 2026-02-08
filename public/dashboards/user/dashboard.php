<?php
// ===============================
// USER DASHBOARD (HOME STYLE)
// ===============================

// Absolute filesystem includes (CORRECT)
require_once __DIR__ . "/../../../config/config.php";
require_once __DIR__ . "/../../../config/database.php";
require_once __DIR__ . "/../../../includes/auth.php";

// Session already started in config.php
requireUser();
$user = getCurrentUser();

// Fetch active products
$sql = "
    SELECT id, title, price, image
    FROM products
    WHERE status = 'active'
    ORDER BY created_at DESC
";
$result = mysqli_query($conn, $sql);
?>

<?php require_once __DIR__ . "/../../../includes/header.php"; ?>

<!-- HERO -->
<section class="home-hero">
    <div class="home-hero-content">
        <h1>Welcome back, <?= htmlspecialchars($user['name']) ?> 👋</h1>

        <p>
            Discover premium home décor & furniture curated just for you.
        </p>

        <div class="home-hero-actions">
            <a href="#products" class="lux-btn">
                Explore Products
            </a>

            <a href="/logout.php" class="lux-btn-outline">
                Logout
            </a>
        </div>
    </div>
</section>

<!-- PRODUCTS -->
<section id="products" class="container home-section">

    <h2>Latest Products</h2>
    <p class="home-muted">Exclusive items available for logged-in users</p>

    <div class="product-grid">

        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>

                <div class="product-card">

                    <div class="product-image">
                        <?php if (!empty($row['image'])): ?>
                            <img src="<?= htmlspecialchars($row['image']) ?>" alt="Product">
                        <?php else: ?>
                            <img src="/assets/images/no-image.png" alt="No Image">
                        <?php endif; ?>
                    </div>

                    <h3><?= htmlspecialchars($row['title']) ?></h3>

                    <p class="price">
                        ₹<?= number_format($row['price'], 2) ?>
                    </p>

                    <a href="/product.php?id=<?= (int)$row['id'] ?>" class="btn">
                        View Product
                    </a>

                </div>

            <?php endwhile; ?>
        <?php else: ?>

            <div class="empty-box">
                <p>No products available right now.</p>
                <small>Please check back later.</small>
            </div>

        <?php endif; ?>

    </div>

</section>

<?php require_once __DIR__ . "/../../../includes/footer.php"; ?>
