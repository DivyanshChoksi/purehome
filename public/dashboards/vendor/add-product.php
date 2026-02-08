<?php
// ===============================
// VENDOR ADD PRODUCT
// ===============================

require_once "../../config/config.php";
require_once "../../config/database.php";
require_once "../../includes/auth.php";
require_once "../../config/cloudinary.php";

use Cloudinary\Api\Upload\UploadApi;

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

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title       = trim($_POST['title']);
    $price       = trim($_POST['price']);
    $description = trim($_POST['description']);

    if ($title === "" || $price === "") {
        $error = "Title and price are required.";
    } else {

        $imageUrl = "";

        // ===============================
        // IMAGE UPLOAD (CLOUDINARY FIRST)
        // ===============================
        if (!empty($_FILES['image']['name'])) {

            try {
                // Try Cloudinary
                require_once "../../config/cloudinary.php";
                use Cloudinary\Api\Upload\UploadApi;

                $upload = (new UploadApi())->upload(
                    $_FILES['image']['tmp_name'],
                    ["folder" => "purehome/products"]
                );

                $imageUrl = $upload['secure_url'];

            } catch (Exception $e) {

                // Fallback to local upload
                $targetDir = "../../uploads/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $fileName = time() . "_" . basename($_FILES["image"]["name"]);
                $targetFile = $targetDir . $fileName;

                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                    $imageUrl = "../../uploads/" . $fileName;
                }
            }
        }

        // Insert product
        $query = "
            INSERT INTO products (vendor_id, title, price, image, description, status)
            VALUES ($user_id, '$title', '$price', '$imageUrl', '$description', 'active')
        ";

        if (mysqli_query($conn, $query)) {
            $success = "Product added successfully!";
        } else {
            $error = "Failed to add product.";
        }
    }
}
?>

<?php require_once "../../includes/header.php"; ?>

<main class="container">

    <h1>Add New Product</h1>

    <?php if ($error): ?>
        <p style="color:red"><?= $error ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color:green"><?= $success ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <label>Product Title</label><br>
        <input type="text" name="title" required><br><br>

        <label>Price</label><br>
        <input type="number" step="0.01" name="price" required><br><br>

        <label>Description</label><br>
        <textarea name="description"></textarea><br><br>

        <label>Product Image</label><br>
        <input type="file" name="image" accept="image/*"><br><br>

        <button type="submit" class="btn">Add Product</button>

    </form>

    <p>
        <a href="dashboard.php">← Back to Dashboard</a>
    </p>

</main>

<?php require_once "../../includes/footer.php"; ?>
