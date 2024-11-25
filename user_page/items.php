<?php
include '../conn/conn.php';

if (isset($_POST['add_to_cart'])) {
    $products_name = $_POST['product_name'];
    $products_price = $_POST['product_price'];
    $products_image = $_POST['product_image'];
    $product_quantity = isset($_POST['product_quantity']) ? $_POST['product_quantity'] : 1; // Default to 1 if not provided

    // Query to check if the product is already in the cart
    $select_cart = $conn->prepare("SELECT COUNT(*) AS count FROM cart WHERE name = ?");
    $select_cart->bind_param("s", $products_name);
    $select_cart->execute();
    $select_cart_result = $select_cart->get_result();
    $row = $select_cart_result->fetch_assoc();

    // Check if product already exists in the cart
    if ($row['count'] > 0) {
        echo "Product already added to cart";
    } else {
        // Insert the product into the cart
        $insert_products = $conn->prepare("INSERT INTO cart (name, price, image, quantity) VALUES (?, ?, ?, ?)");
        $insert_products->bind_param("sdsi", $products_name, $products_price, $products_image, $product_quantity);
        if ($insert_products->execute()) {
            echo "Product added to cart";
        } else {
            echo "Failed to add product to cart";
        }
    }
}

// Fetch product details from the database based on the given product ID
$product_id = $_GET['id'];

// Fetch the current product details
$query = "SELECT name, price, image, description, stock FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product_result = $stmt->get_result();
$product = $product_result->fetch_assoc(); // Fetch the product data

// Fetch related products from the same category (or any other criteria) excluding the current product
$related_query = "SELECT id, name, price, image FROM products WHERE id != ? LIMIT 3"; // Fetch 3 related products
$related_stmt = $conn->prepare($related_query);
$related_stmt->bind_param("i", $product_id);
$related_stmt->execute();
$related_products_result = $related_stmt->get_result();
$related_products = $related_products_result->fetch_all(MYSQLI_ASSOC); // Fetch all related products
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Details</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
    <!-- Navigation -->
<?php include("../includes/topbar1.php"); ?>

<!-- Product section -->
<?php
// Query to fetch only one product
$select_product = $conn->query("SELECT * FROM `products` LIMIT 1");

if ($select_product && $select_product->num_rows > 0) {
    $fetch_product = $select_product->fetch_assoc();

    // Check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_image = $_POST['product_image'];
        $product_quantity = $_POST['product_quantity'];

        // Check if the product already exists in the cart
        $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE name = ?");
        $check_cart->bind_param("s", $product_name);
        $check_cart->execute();
        $result = $check_cart->get_result();

        if ($result->num_rows > 0) {
            // If product exists, set the quantity to the submitted value
            $update_cart = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE name = ?");
            $update_cart->bind_param("is", $product_quantity, $product_name);
            if ($update_cart->execute()) {
                echo "<div class='alert alert-success'>Product quantity updated in cart successfully!</div>";
            } else {
                echo "<div class='alert alert-danger'>Failed to update product quantity in cart.</div>";
            }
        } else {
            // If product doesn't exist, insert new entry
            $insert_cart = $conn->prepare("INSERT INTO `cart` (name, price, image, quantity) VALUES (?, ?, ?, ?)");
            $insert_cart->bind_param("sdsi", $product_name, $product_price, $product_image, $product_quantity);
            if ($insert_cart->execute()) {
                echo "<div class='alert alert-success'>Product added to cart successfully!</div>";
            } else {
                echo "<div class='alert alert-danger'>Failed to add product to cart.</div>";
            }
        }
    }
?>


<section class="py-5">
    <div class="container px-4 px-lg-5 my-5">
        <form method="post" action="">
            <div class="row gx-4 gx-lg-5 align-items-center">
                <div class="col-md-6">
                    <img class="card-img-top mb-5 mb-md-0" src="../admin_page/foodMenu/uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Main Image" />
                </div>

                <div class="col-md-6">
                    <h1 class="display-5 fw-bolder"><?php echo htmlspecialchars($product['name']); ?></h1>
                    <div class="fs-5 mb-5">
                        <span>₱<?php echo number_format($product['price'], 2); ?></span>
                    </div>
                    <p class="lead"><?php echo htmlspecialchars($product['description']); ?></p>
                    <p>Stock: <?php echo $product['stock']; ?></p>
                    <div class="d-flex">
                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                        <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($product['price']); ?>">
                        <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($product['image']); ?>">
                        <input type="number" name="product_quantity" value="1" min="1" class="form-control text-center me-3" id="inputQuantity" style="max-width: 6rem" />
                        <input type="submit" class="btn btn-outline-dark flex-shrink-0" value="Add to Cart" name="add_to_cart">
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<?php
} else {
    echo "<div class='empty_text'>No Products Available</div>";
}
?>
    <!-- Related Products Section -->
    <h2>Related Products</h2>
    <section class="py-5 bg-light related-products">
        <div class="container">
            <div class="related-products-grid row">
                <!-- Loop through related products -->
                <?php foreach ($related_products as $related_product): ?>
                    <div class="related-product-item col-md-3">
                        <img src="../admin_page/foodMenu/uploads/<?php echo htmlspecialchars($related_product['image']); ?>" alt="Related Product" class="img-fluid" />
                        <p><?php echo htmlspecialchars($related_product['name']); ?></p>
                        <p>₱<?php echo number_format($related_product['price'], 2); ?></p>
                        <a href="items.php?id=<?php echo $related_product['id']; ?>" class="btn btn-primary">View Product</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    

    <!-- Bootstrap core JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
