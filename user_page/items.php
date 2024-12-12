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

<?php
require_once '../endpoint/session_config.php';
include '../conn/conn.php';

// ... (keep existing PHP logic until the HTML part)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Details</title>
    <link href="css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .path {
            padding: 1rem 0;
            color: #666;
        }
        .path span {
            color: #000;
        }
        .thumbnail-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-right: 15px;
        }
        .thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            cursor: pointer;
            border: 1px solid #ddd;
        }
        .thumbnail:hover {
            border-color: #ff0000;
        }
        .main-image-container {
            display: flex;
            align-items: flex-start;
        }
        .main-image {
            flex-grow: 1;
            max-width: calc(100% - 100px);
            height: 500px;
            object-fit: contain;
        }
        .product-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .chili-rating {
            color: #ff0000;
            margin-bottom: 15px;
        }
        .product-price {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .product-description {
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .quantity-input {
            width: 100px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .add-to-cart {
            background-color: #ff0000;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        .wishlist-btn {
            border: 1px solid #000;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            background: transparent;
        }
        .wishlist-btn i {
            color: transparent;
            -webkit-text-stroke: 1px black;
        }
        .row {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 0;
            display: flex;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <?php include("../includes/topbar1.php"); ?>
    <div class="path">Products / <span><?php echo htmlspecialchars($product['name']); ?></span></div>  
    <div class="container py-5">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="main-image-container">
                    <div class="thumbnail-container">
                        <?php 
                        // Assuming you have multiple images stored as image1, image2, etc.
                        //papaltan pa to 
                        for($i = 1; $i <= 4; $i++): 
                        ?>
                            <img src="../admin_page/foodMenu/uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                                 class="thumbnail" 
                                 onclick="updateMainImage(this.src)"
                                 alt="Product thumbnail <?php echo $i; ?>">
                        <?php endfor; ?>
                    </div>
                    <img id="mainImage" 
                         src="../admin_page/foodMenu/uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                         class="img-fluid main-image" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
            </div>
            
            <div class="col-md-6">
                <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <div class="chili-rating">
                    <?php for($i = 0; $i < 5; $i++): ?>
                        <i class="fas fa-pepper-hot"></i>
                    <?php endfor; ?>
                </div>
                
                <div class="product-price">
                    ₱<?php echo number_format($product['price'], 2); ?>
                </div>
                
                <p class="product-description">
                    <?php echo htmlspecialchars($product['description']); ?>
                </p>
                
                <hr>
                
                <form method="post" class="mb-3">
                    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                    <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($product['price']); ?>">
                    <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($product['image']); ?>">
                    
                    <div class="action-buttons">
                        <input type="number" 
                               name="product_quantity" 
                               value="1" 
                               min="1" 
                               max="<?php echo $product['stock']; ?>" 
                               class="quantity-input"
                               <?php if ($product['stock'] === 0) echo 'disabled'; ?>>
                        
                        <button type="submit" 
                                name="add_to_cart" 
                                class="add-to-cart">
                            Add to Cart
                        </button>
                        
                        <button type="submit" 
                                name="add_to_wishlist" 
                                class="wishlist-btn">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </form>

                <?php if (isset($wishlist_message)): ?>
                    <p class="text-info mt-2"><?php echo $wishlist_message; ?></p>
                <?php endif; ?>
                
                <?php if ($product['stock'] === 0): ?>
                    <p class="text-danger">This product is out of stock.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function updateMainImage(src) {
            document.getElementById('mainImage').src = src;
        }
    </script>
</body>
</html>

