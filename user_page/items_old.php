<?php
require_once '../endpoint/session_config.php';
include '../conn/conn.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch product ID and validate it
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid product ID.");
}

$product_id = intval($_GET['id']); // Ensure numeric value

// Fetch current product details
$query = "SELECT name, price, image, description, stock FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product_result = $stmt->get_result();
$product = $product_result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

// Fetch related products
$related_query = "SELECT id, name, price, image FROM products WHERE id != ? LIMIT 3";
$related_stmt = $conn->prepare($related_query);
$related_stmt->bind_param("i", $product_id);
$related_stmt->execute();
$related_products_result = $related_stmt->get_result();
$related_products = $related_products_result->fetch_all(MYSQLI_ASSOC);

// Add to cart logic
if (isset($_POST['add_to_cart'])) {
    // ... (Existing add-to-cart logic)
}

// Add to wishlist logic
if (isset($_POST['add_to_wishlist'])) {
    $tbl_user_id = $_SESSION['tbl_user_id']; // Assuming you store the user ID in the session

    // Check if the product is already in the wishlist
    $check_wishlist = $conn->prepare("SELECT * FROM wishlist WHERE product_id = ? AND tbl_user_id = ?");
    $check_wishlist->bind_param("ii", $product_id, $tbl_user_id);
    $check_wishlist->execute();
    $wishlist_result = $check_wishlist->get_result();

    if ($wishlist_result->num_rows > 0) {
        $wishlist_message = "This product is already in your wishlist.";
    } else {
        // Add product to the wishlist
        $insert_wishlist = $conn->prepare("INSERT INTO wishlist (tbl_user_id, product_id) VALUES (?, ?)");
        $insert_wishlist->bind_param("ii", $tbl_user_id, $product_id);
        if ($insert_wishlist->execute()) {
            $wishlist_message = "Product added to your wishlist.";
        } else {
            $wishlist_message = "Failed to add product to wishlist.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Details</title>
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
<?php include("../includes/topbar1.php"); ?>

<section class="py-5">
    <div class="path">Products / <?php echo htmlspecialchars($product['name']); ?></div>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <img src="../admin_page/foodMenu/uploads/<?php echo htmlspecialchars($product['image']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="col-md-6">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <p>₱<?php echo number_format($product['price'], 2); ?></p>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <p>Stock: <?php echo $product['stock']; ?></p>
                <form method="post" class="mb-3">
                    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                    <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($product['price']); ?>">
                    <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($product['image']); ?>">
                    <input
                    type="number"
                    name="product_quantity"
                    value="1"
                    min="1"
                    max="<?php echo $product['stock']; ?>"
                    class="form-control mb-2"
                    <?php if ($product['stock'] === 0) echo 'disabled'; ?>>

                    <!-- Add to Cart Button -->
                    <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                </form>

                <!-- Add to Wishlist Button -->
                <form method="post">
                    <button type="submit" name="add_to_wishlist" class="btn btn-outline-danger">Add to Wishlist</button>
                </form>

                <!-- Messages -->
                <?php if (isset($wishlist_message)) echo "<p class='text-info mt-2'>$wishlist_message</p>"; ?>
                <?php if (isset($success_message)) echo "<p class='text-success'>$success_message</p>"; ?>
                <?php if (isset($error_message)) echo "<p class='text-danger'>$error_message</p>"; ?>

                <?php if ($product['stock'] === 0): ?>
                <p class="text-danger">This product is out of stock.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container text-center">
        <h2>Related Products</h2>
        <div class="row justify-content-center align-items-start related-products">
            <?php foreach ($related_products as $related): ?>
                <div class="col-md-4">
                    <img src="../admin_page/foodMenu/uploads/<?php echo htmlspecialchars($related['image']); ?>" class="img-fluid mb-2">
                    <p><?php echo htmlspecialchars($related['name']); ?></p>
                    <p>₱<?php echo number_format($related['price'], 2); ?></p>
                    <a href="items.php?id=<?php echo $related['id']; ?>" class="btn btn-secondary">View</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script src="js/scripts.js"></script>
</body>
</html>
