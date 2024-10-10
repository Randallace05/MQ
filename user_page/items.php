<?php
include '../conn/conn.php';

// Fetch product details from the database based on the given product ID
$product_id = $_GET['id'];

// Fetch the current product details
$query = "SELECT name, price, image, description, stock FROM products WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
$stmt->execute();

$product = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the product data

// Fetch related products from the same category (or any other criteria) excluding the current product
$related_query = "SELECT id, name, price, image FROM products WHERE id != :id LIMIT 3"; // Fetch 3 related products
$related_stmt = $conn->prepare($related_query);
$related_stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
$related_stmt->execute();

$related_products = $related_stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all related products
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
    <?php include("../includes/topbar.php"); ?>

    <!-- Product section -->
    <section class="py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="row gx-4 gx-lg-5 align-items-center">
                <div class="col-md-6">
                    <img class="card-img-top mb-5 mb-md-0" src="../admin_page/foodMenu/uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Main Image" />
                </div>

                <div class="col-md-6">
                    <h1 class="display-5 fw-bolder"><?php echo htmlspecialchars($product['name']); ?></h1>
                    <div class="fs-5 mb-5">
                        <span>$<?php echo number_format($product['price'], 2); ?></span>
                    </div>
                    <p class="lead"><?php echo htmlspecialchars($product['description']); ?></p>
                    <p>Stock: <?php echo $product['stock']; ?></p>
                    <div class="d-flex">
                        <input class="form-control text-center me-3" id="inputQuantity" type="number" value="1" style="max-width: 3rem" />
                        <button class="btn btn-outline-dark flex-shrink-0" type="button">
                            <i class="bi-cart-fill me-1"></i>
                            <a href="../cart.php">Add to cart</a>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; Your Website 2023</p>
        </div>
    </footer>

    <!-- Bootstrap core JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
