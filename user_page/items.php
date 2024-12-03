<?php
session_start();
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
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = isset($_POST['product_quantity']) ? intval($_POST['product_quantity']) : 1;
    $tbl_user_id = $_SESSION['tbl_user_id']; // Assuming you store the user ID in the session

    // Check if the user ID exists in tbl_user
    $check_user = $conn->prepare("SELECT tbl_user_id FROM tbl_user WHERE tbl_user_id = ?");
    $check_user->bind_param("i", $tbl_user_id);
    $check_user->execute();
    $user_result = $check_user->get_result();

    if ($user_result->num_rows === 0) {
        echo "<div class='alert alert-danger'>Error: User does not exist.</div>";
        exit;
    }

    // Check if product is already in the cart
    $check_cart = $conn->prepare("SELECT * FROM cart WHERE name = ? AND tbl_user_id = ?");
    $check_cart->bind_param("si", $product_name, $tbl_user_id);
    $check_cart->execute();
    $cart_result = $check_cart->get_result();

    if ($cart_result->num_rows > 0) {
        // Update quantity if product exists
        $update_cart = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE name = ? AND tbl_user_id = ?");
        $update_cart->bind_param("isi", $product_quantity, $product_name, $tbl_user_id);
        if ($update_cart->execute()) {
            $success_message = "Product quantity updated in cart.";
        } else {
            $error_message = "Failed to update cart.";
        }
    } else {
        // Insert new product into the cart
        $insert_cart = $conn->prepare("INSERT INTO cart (name, price, image, quantity, tbl_user_id) VALUES (?, ?, ?, ?, ?)");
        $insert_cart->bind_param("sdsii", $product_name, $product_price, $product_image, $product_quantity, $tbl_user_id);
        if ($insert_cart->execute()) {
            $success_message = "Product added to cart successfully.";
        } else {
            $error_message = "Failed to add product to cart.";
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
<style>
    .related-products {
    display: flex;
    justify-content: center; /* Centers the items horizontally */
    align-items: flex-start; /* Aligns items at the top */
    gap: 30px; /* Adds spacing between the items */
    flex-wrap: nowrap; /* Ensures they stay in one row */
}

.related-products .col-md-4 {
    flex: 0 0 30%; /* Each product takes up 30% of the row width */
    max-width: 300px; /* Set a max width for consistency */
    text-align: center; /* Centers text inside each product card */
}

.related-products img {
    max-width: 100%; /* Ensures images are responsive */
    height: auto; /* Maintains aspect ratio */
}

</style>
<body>
<?php include("../includes/topbar1.php"); ?>

<section class="py-5">
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
                <form method="post">
                    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                    <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($product['price']); ?>">
                    <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($product['image']); ?>">
                    <input type="number" name="product_quantity" value="0" min="1" class="form-control mb-2">
                    <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                </form>
                <?php if ($product['stock'] === 0): ?>
                <p class="text-danger">This product is out of stock.</p>
                <?php endif; ?>
                <?php if (isset($success_message)) echo "<p class='text-success'>$success_message</p>"; ?>
                <?php if (isset($error_message)) echo "<p class='text-danger'>$error_message</p>"; ?>
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
