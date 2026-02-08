<?php
// ===============================
// VENDOR PRODUCTS PAGE
// ===============================

require_once "../../config/config.php";
require_once "../../config/database.php";
require_once "../../includes/auth.php";

// Login required
requireLogin();

// Only vendor allowed
if ($_SESSION['user']['role'] !== 'vendor') {
    header("Location: ../../public/login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

// Check vendor approval
$vendorCheck = mysqli_query(
    $conn,
    "SELECT status FROM vendor_profiles WHERE user_id = $user_id LIMIT 1"
);

if (mysqli_num_rows($vendorCheck) === 0) {
    header("Location: complete-profile.php");
    exit;
}

$vendor = mysqli_fetch_assoc($vendorCheck);

if ($vendor['status'] !== 'approved') {
    echo "<h3>Your vendor account is not approved yet.</h3>";
    echo '<a href="../../public/logout.php">Logout</a>';
    exit;
}

// DELETE PRODUCT
if (isset($_GET['delete'])) {

    $product_id = (int) $_GET['delete'];

    // Ensure product belongs to this vendor
    mysqli_query(
        $conn,
        "DELETE FROM products WHERE id = $product_id AND vendor_id = $user_id"
    );

    header("Location: products.php");
    exit;
}

// FETCH PRODUCTS
$result = mysqli_query(
    $conn,
    "SELECT id, title, price, image, created_at 
     FROM products 
     WHERE vendor_id = $user_id 
     ORDER BY created_at DESC"
);
?>

<?php require_once "../../includes/header.php"; ?>

<main class="container">

    <h1>My Products</h1>

    <p>
        <a href="add-product.php" class="btn">➕ Add New Product</a>
    </p>

    <?php if (mysqli_num_rows($result) === 0): ?>
        <p>No products found.</p>
    <?php else: ?>

        <table border="1" cellpadding="10" cellspacing="0" width="100%">

            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Price</th>
                <th>Added On</th>
                <th>Action</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>

                    <td>
                        <?php if ($row['image']): ?>
                            <img src="<?= htmlspecialchars($row['image']) ?>" width="60">
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>

                    <td><?= htmlspecialchars($row['title']) ?></td>

                    <td>₹<?= number_format($row['price'], 2) ?></td>

                    <td><?= date("d M Y", strtotime($row['created_at'])) ?></td>

                    <td>
                        <a href="products.php?delete=<?= $row['id'] ?>"
                           onclick="return confirm('Delete this product?')">
                           ❌ Delete
                        </a>
                    </td>

                </tr>
            <?php endwhile; ?>

        </table>

    <?php endif; ?>

    <p>
        <a href="dashboard.php">← Back to Dashboard</a>
    </p>

</main>

<?php require_once "../../includes/footer.php"; ?>
