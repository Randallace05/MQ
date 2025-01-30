<?php
session_start();
include '../conn/conn.php';

$product_id = intval($_GET['id'] ?? 0);

// Validate that product_id is provided
if ($product_id <= 0) {
    $_SESSION['error_message'] = "Invalid product. Please access this page from the product list.";
    header("Location: ../user_page/products.php"); // Redirect to product list
    exit;
}

// Check if the order is eligible for review
$show_review_form = isset($_SESSION['tbl_user_id']);


// Modified review form visibility logic (Removed old logic)


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT p.id, p.name, p.price, p.image, p.other_images, p.description,
                     pb.stock, pb.batch_codename
              FROM products p
              LEFT JOIN product_batches pb ON p.id = pb.product_id
              WHERE p.id = ? AND (pb.status = 1 OR pb.status IS NULL)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Decode the other_images field
        $other_images = !empty($product['other_images']) ? json_decode($product['other_images'], true) : [];

        if (is_array($other_images)) {
            foreach ($other_images as $image) {
            }
        } else {
            echo "No additional images available.";
        }
    } else {
        echo "Product not found.";
        exit();
    }
} else {
    echo "No product specified.";
    exit();
}


//add to cart logiv
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['tbl_user_id'])) {
        echo "<script>alert('Please log in to add items to your cart.');</script>";
    } else {
        $user_id = $_SESSION['tbl_user_id'];
        $product_id = $id;
        $quantity = $_POST['quantity']; // Ensure this is posted from the form
        $product_name = $product['name'];
        $product_price = floatval($product['price']);
        $product_image = $product['image'];
        $total_price = $product_price * $quantity;

        // Check if the product is already in the cart
        $check_cart = $conn->prepare("SELECT * FROM cart WHERE tbl_user_id = ? AND product_id = ?");
        $check_cart->bind_param("ii", $user_id, $product_id);
        $check_cart->execute();
        $cart_result = $check_cart->get_result();

        if ($cart_result->num_rows > 0) {
            // Update quantity if product is already in cart
            $update_cart = $conn->prepare("UPDATE cart SET quantity = quantity + ?, price = ?, total_price = total_price + ? WHERE tbl_user_id = ? AND product_id = ?");
            $update_cart->bind_param("idiii", $quantity, $product_price, $total_price, $user_id, $product_id);
            $update_cart->execute();
        } else {
            // Add new item to cart
            $add_to_cart = $conn->prepare("INSERT INTO cart (tbl_user_id, product_id, quantity, batch_codename, name, image, price, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $add_to_cart->bind_param("iiisssdd", $user_id, $product_id, $quantity, $product['batch_codename'], $product_name, $product_image, $product_price, $total_price);
            $add_to_cart->execute();
        }

        // Reduce stock in the product_batches table
        $reduce_stock = $conn->prepare("UPDATE product_batches SET stock = stock - ? WHERE product_id = ? AND status = 1");
        $reduce_stock->bind_param("ii", $quantity, $product_id);
        $reduce_stock->execute();

        echo "<script>alert('Product added to cart successfully!');</script>";
    }
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

// Handle review submission
if (isset($_POST['submit_review'])) {
    $tbl_user_id = $_SESSION['tbl_user_id'] ?? null;
    if (!$tbl_user_id) {
        $_SESSION['error_message'] = "You need to log in to submit a review.";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    $review_text = trim($_POST['review_text']);
    $rating = intval($_POST['rating']);
    $is_anonymous = isset($_POST['is_anonymous']) ? 1 : 0;
    $username = $is_anonymous ? 'Anonymous' : ($_SESSION['username'] ?? 'Anonymous');

    if (empty($review_text) || $rating < 1 || $rating > 5) {
        $_SESSION['error_message'] = "Please provide both a valid rating (1-5) and a review text.";
    } else {
        $insert_review = $conn->prepare("INSERT INTO reviews (product_id, tbl_user_id, username, review_text, rating, is_anonymous) VALUES (?, ?, ?, ?, ?, ?)");
        $insert_review->bind_param("iissii", $product_id, $tbl_user_id, $username, $review_text, $rating, $is_anonymous);

        if ($insert_review->execute()) {
            $_SESSION['success_message'] = "Review submitted successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to submit review. Error: " . $conn->error;
        }
        $insert_review->close();
    }
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

// Fetch existing reviews for the product
$review_query = "SELECT username, review_text, rating, created_at, is_anonymous FROM reviews WHERE product_id = ? ORDER BY created_at DESC";
$review_stmt = $conn->prepare($review_query);
$review_stmt->bind_param("i", $product_id);
$review_stmt->execute();
$reviews_result = $review_stmt->get_result();
$reviews = $reviews_result->fetch_all(MYSQLI_ASSOC);

$total_rating = 0;
$review_count = count($reviews);
foreach ($reviews as $review) {
    $total_rating += $review['rating'];
}
$average_rating = $review_count > 0 ? round($total_rating / $review_count, 1) : 0;

// Display error or success messages
if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']);
}
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Product Details - <?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="icon" type="image/x-icon" href="../user_page/assets/sili.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 15px;
        }

        .path {
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }

        .path span {
            font-weight: bold;
            color: #000;
        }

        .main-image-container {
            display: flex;
            gap: 20px;
        }

        .thumbnail-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .thumbnail {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border: 2px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            transition: transform 0.3s ease, border-color 0.3s ease;
        }

        .thumbnail:hover {
            transform: scale(1.1);
            border-color: #ff0000;
        }

        .main-image {
            width: 100%;
            height: auto;
            max-height: 500px;
            border-radius: 8px;
            border: 2px solid #ddd;
            object-fit: contain;
        }

        .product-title {
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .chili-rating i {
            color: #ff0000;
            font-size: 18px;
            margin-right: 2px;
        }

        .product-price {
            font-size: 22px;
            font-weight: bold;
            color: #27ae60;
            margin-bottom: 15px;
        }

        .product-description {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }

        .quantity-input {
            width: 60px;  /* Reduce width */
            height: 30px;  /* Reduce height */
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 3px;
            font-size: 14px;
            text-align: center;
        }


        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .add-to-cart {
            background-color: #ff0000;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-to-cart:hover {
            background-color: #cc0000;
        }

        .wishlist-btn {
            background: none;
            border: 2px solid #ff0000;
            padding: 10px;
            border-radius: 4px;
            color: #ff0000;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .wishlist-btn:hover {
            background-color: #ff0000;
            color: white;
        }

        /* Customer Review Section */
        .review {
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .review h5 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .review small {
            font-size: 12px;
            color: #999;
        }

        .review p {
            margin-top: 10px;
            color: #555;
            font-size: 14px;
        }

        /* Review Submission Form */
        form.bg-light {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        textarea.form-control {
            resize: none;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
        }

        .btn-primary {
            background-color: #ff0000;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .chili-rating {
        font-size: 24px;
        cursor: pointer;
        }

        .chili-rating .chili {
            color: #c2bdbd;
            transition: color 0.2s ease;
        }

        .chili-rating .chili.active {
            color: #ff0000;
        }

        .review-form {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .anonymous-toggle {
             margin-top: 10px;
        }

        .btn-primary:hover {
            background-color: #cc0000;
        }


        @media (max-width: 768px) {
            .main-image-container {
                flex-direction: column;
            }

            .main-image {
                max-height: 300px;
            }

            .thumbnail-container {
                flex-direction: row;
                gap: 5px;
                overflow-x: auto;
            }

            .thumbnail {
                flex-shrink: 0;
                width: 60px;
                height: 60px;
            }
        }

    .rating-summary {
        background: #fff;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .overall-rating {
        font-size: 2.5rem;
        font-weight: bold;
        color: #ff0000;
    }

    .filter-btn {
        padding: 0.5rem 1rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: #fff;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-btn:hover, .filter-btn.active {
        background: #ff0000;
        color: white;
        border-color: #ff0000;
    }

    .review-item {
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .review-avatar img {
        object-fit: cover;
        border: 2px solid #ddd;
    }

    .pagination .btn {
        min-width: 40px;
    }

    .chili-rating {
        font-size: 1rem;
    }

    .chili-rating i {
        margin-right: 2px;
    }

    #reviews-section {
        scroll-margin-top: 20px;
    }

    @media (max-width: 768px) {
        .rating-filters {
            flex-wrap: wrap;
        }

        .filter-btn {
            font-size: 0.875rem;
        }
    }
    </style>
<body>
    <?php include("../includes/topbar1.php"); ?>
    <div class="path">Products / <span><?php echo htmlspecialchars($product['name']); ?></span></div>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="main-image-container">
                    <div class="thumbnail-container">
                        <!-- Other image/s -->
                        <?php foreach ($other_images as $i => $image): ?>
                            <img src="../admin_page/foodMenu/uploads/<?php echo htmlspecialchars($image); ?>"
                                class="thumbnail"
                                onclick="updateMainImage(this.src)"
                                alt="Product thumbnail <?php echo $i + 1; ?>">
                        <?php endforeach; ?>
                                        </div>
                                        <img id="mainImage"
                                            src="../admin_page/foodMenu/uploads/<?php echo htmlspecialchars($product['image']); ?>"
                                            class="img-fluid main-image"
                                            alt="<?php echo htmlspecialchars($product['name']); ?>">
                                    </div>
                                </div>
            <div class="col-md-6">
                <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>

                <!-- average of rating -->
                <div class="chili-rating">
                <?php
                            $full_chilies = floor($average_rating);
                            $half_chili = $average_rating - $full_chilies >= 0.5;
                            for ($i = 1; $i <= 5; $i++):
                                if ($i <= $full_chilies): ?>
                                    <i class="fas fa-pepper-hot" style="color: #ff0000;"></i>
                                <?php elseif ($i == $full_chilies + 1 && $half_chili): ?>
                                    <i class="fas fa-pepper-hot" style="color: #ff0000; opacity: 0.5;"></i>
                                <?php else: ?>
                                    <i class="fas fa-pepper-hot" style="color: #c2bdbd;"></i>
                                <?php endif;
                            endfor; ?>
                            <span>(<?php echo number_format($average_rating, 1); ?>)</span>
                </div>
                <p>Stock: <?php echo htmlspecialchars($product['stock']); ?></p>
                <p>Batch Codename: <?php echo htmlspecialchars($product['batch_codename']); ?></p>

                <div class="product-price">
                    ₱<?php echo number_format($product['price'], 2); ?>
                </div>

                <p class="product-description">
                    <?php echo htmlspecialchars($product['description']); ?>
                </p>

                <hr>

                <form method="post">
                <div class="form-group"> 
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1"
                            max="<?php echo htmlspecialchars($product['stock']); ?>" class="quantity-input">
                    </div>
                    <button type="submit" name="add_to_cart" class="btn btn-primary mt-3">Add to Cart</button>
                </form>

                <?php if (isset($wishlist_message)): ?>
                    <p class="text-info mt-2"><?php echo $wishlist_message; ?></p>
                <?php endif; ?>

                <?php if ($product['stock'] === 0): ?>
                    <p class="text-danger">This product is out of stock.</p>
                <?php endif; ?>
            </div>

    <h3 class="mb-4">Customer Reviews</h3>

    <!-- Review Submission Form -->
    <div id="review-section">
        <h3>Write a Review</h3>
        <?php if (isset($_SESSION['unique_id'])): ?>
            <form method="post" class="review-form">
                <div class="mb-3">
                    <label for="rating" class="form-label">Rating:</label>
                    <div class="chili-rating submission-chilies">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="fa-solid fa-pepper-hot chili"
                               data-rating="<?php echo $i; ?>"
                               style="color: #c2bdbd; --fa-rotate-angle: 320deg;">
                            </i>
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" name="rating" id="rating" value="0" required>
                </div>
                <div class="mb-3">
                    <label for="review_text" class="form-label">Your Review:</label>
                    <textarea name="review_text" id="review_text" rows="3" class="form-control" required></textarea>
                </div>
                <div class="mb-3 form-check anonymous-toggle">
                    <input type="checkbox" class="form-check-input" id="is_anonymous" name="is_anonymous">
                    <label class="form-check-label" for="is_anonymous">Post anonymously</label>
                </div>
                <button type="submit" name="submit_review" class="btn btn-primary">Submit Review</button>
            </form>
        <?php else: ?>
            <p><a href="../user_page/login.php">Log in</a> to write a review.</p>
        <?php endif; ?>
    </div>

 <!-- Product Ratings Section -->
<div class="container mt-5" id="reviews-section">
    <h2 class="mb-4">Product Ratings</h2>

    <!-- Overall Rating Display -->
    <div class="rating-summary bg-white p-4 rounded-lg shadow-sm mb-4">
        <div class="d-flex align-items-center gap-4">
            <div>
                <div class="overall-rating text-4xl font-bold text-red-500">
                    <?php echo number_format($average_rating, 1); ?>
                </div>
                <div class="text-gray-600">out of 5</div>
            </div>
            <div class="flex-grow-1">
                <div class="chili-rating">
                    <?php
                    $full_chilies = floor($average_rating);
                    $half_chili = $average_rating - $full_chilies >= 0.5;
                    for ($i = 1; $i <= 5; $i++):
                        if ($i <= $full_chilies): ?>
                            <i class="fas fa-pepper-hot" style="color: #ff0000;"></i>
                        <?php elseif ($i == $full_chilies + 1 && $half_chili): ?>
                            <i class="fas fa-pepper-hot" style="color: #ff0000; opacity: 0.5;"></i>
                        <?php else: ?>
                            <i class="fas fa-pepper-hot" style="color: #c2bdbd;"></i>
                        <?php endif;
                    endfor; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Filters -->
    <div class="rating-filters d-flex gap-2 mb-4">
        <?php
        // Get count for each rating
        $rating_counts = [];
        $stmt = $conn->prepare("SELECT rating, COUNT(*) as count FROM reviews WHERE product_id = ? GROUP BY rating ORDER BY rating DESC");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $rating_counts[$row['rating']] = $row['count'];
        }

        // Calculate total reviews
        $total_reviews = array_sum($rating_counts);
        ?>

        <button class="filter-btn active" data-rating="all">
            All (<?php echo $total_reviews; ?>)
        </button>
        <?php for ($i = 5; $i >= 1; $i--): ?>
            <button class="filter-btn" data-rating="<?php echo $i; ?>">
                <?php echo $i; ?> Star (<?php echo $rating_counts[$i] ?? 0; ?>)
            </button>
        <?php endfor; ?>
    </div>
</div>
    <!-- Reviews List -->
    <div class="reviews-container">
        <?php
        // Get current page from URL or AJAX request
        $page = isset($_GET['review_page']) ? (int)$_GET['review_page'] : 1;
        $reviews_per_page = 3;
        $offset = ($page - 1) * $reviews_per_page;

        // Modify query based on filter
        $rating_filter = isset($_GET['rating']) ? (int)$_GET['rating'] : 0;
        $rating_condition = $rating_filter > 0 ? "AND r.rating = ?" : "";

        $review_query = "SELECT r.*, u.img
                        FROM reviews r
                        LEFT JOIN tbl_user u ON r.tbl_user_id = u.tbl_user_id
                        WHERE r.product_id = ? $rating_condition
                        ORDER BY r.created_at DESC
                        LIMIT ? OFFSET ?";

        $stmt = $conn->prepare($review_query);
        if ($rating_filter > 0) {
            $stmt->bind_param("iiii", $product_id, $rating_filter, $reviews_per_page, $offset);
        } else {
            $stmt->bind_param("iii", $product_id, $reviews_per_page, $offset);
        }
        $stmt->execute();
        $reviews = $stmt->get_result();

        while ($review = $reviews->fetch_assoc()):
        ?>
        <br>
            <div class="review-item bg-white p-4 rounded-lg shadow-sm mb-4" data-rating="<?php echo $review['rating']; ?>">
                <div class="d-flex gap-4">
                    <!-- User Profile Image -->
                    <div class="review-avatar">
                        <?php if ($review['is_anonymous']): ?>
                            <img src="../assets/default-avatar.png" alt="Anonymous" class="rounded-circle" width="48" height="48">
                        <?php else: ?>
                            <img src="<?php echo $review['profile_image'] ?? '../assets/default-avatar.png'; ?>"
                                 alt="<?php echo htmlspecialchars($review['username']); ?>"
                                 class="rounded-circle"
                                 width="48"
                                 height="48">
                        <?php endif; ?>
                    </div>

                    <!-- Review Content -->
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="mb-0">
                                    <?php echo htmlspecialchars($review['is_anonymous'] ? 'Anonymous' : $review['username']); ?>
                                </h5>
                                <div class="text-gray-500 text-sm">
                                    <?php echo date("Y-m-d H:i", strtotime($review['created_at'])); ?>
                                </div>
                            </div>
                            <div class="chili-rating">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class="fa-solid fa-pepper-hot <?php echo $i <= $review['rating'] ? 'active' : ''; ?>"
                                       style="color: <?php echo $i <= $review['rating'] ? '#ff0000' : '#c2bdbd'; ?>;">
                                    </i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <p class="review-text mb-0">
                            <?php echo nl2br(htmlspecialchars($review['review_text'])); ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>

         <!-- Pagination -->
         <?php
        $total_reviews_query = "SELECT COUNT(*) as total FROM reviews WHERE product_id = ? " .
                              ($rating_filter > 0 ? "AND rating = ?" : "");
        $stmt = $conn->prepare($total_reviews_query);
        if ($rating_filter > 0) {
            $stmt->bind_param("ii", $product_id, $rating_filter);
        } else {
            $stmt->bind_param("i", $product_id);
        }
        $stmt->execute();
        $total_reviews = $stmt->get_result()->fetch_assoc()['total'];
        $total_pages = ceil($total_reviews / $reviews_per_page);

        if ($total_pages > 1):
        ?>
            <div class="pagination d-flex justify-content-center align-items-center gap-2 mt-4">
                <a href="javascript:void(0)"
                   onclick="loadReviewPage(<?php echo max(1, $page - 1); ?>)"
                   class="btn btn-outline-primary <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                    Previous
                </a>

                <?php
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $start_page + 4);
                if ($end_page - $start_page < 4) {
                    $start_page = max(1, $end_page - 4);
                }
                for ($i = $start_page; $i <= $end_page; $i++):
                ?>
                    <a href="javascript:void(0)"
                       onclick="loadReviewPage(<?php echo $i; ?>)"
                       class="btn <?php echo $i === $page ? 'btn-primary' : 'btn-outline-primary'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <a href="javascript:void(0)"
                   onclick="loadReviewPage(<?php echo min($total_pages,$page + 1); ?>)"
                   class="btn btn-outline-primary <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                    Next
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function updateMainImage(src) {
            document.getElementById('mainImage').src = src;
        }

        document.addEventListener('DOMContentLoaded', () )=> {
    // Submission chilies
    constaddEventListener('DOMContentLoaded', () => {
    // Submission chilies
    const submissionChilies = document.querySelectorAll('.submission-chilies .chili');
    const ratingInput = document.getElementById('rating');

    submissionChilies.forEach((chili, index) => {
        chili.addEventListener('mouseover', () => {
            submissionChilies.forEach((c, i) => {
                c.style.color = i <= index ? '#ff0000' : '#c2bdbd';
            });
        });

        chili.addEventListener('mouseout', () => {
            submissionChilies.forEach((c, i) => {
                c.style.color = i < ratingInput.value ? '#ff0000' : '#c2bdbd';
            });
        });

        chili.addEventListener('click', () => {
            ratingInput.value = index + 1;
            submissionChilies.forEach((c, i) => {
                c.classList.toggle('active', i <= index);
            });
        });
    });
});
        }

</script>

<script>
function loadReviewPage(page, rating = 0) {
    const reviewsSection = document.querySelector('.reviews-container');

    // Create URL with parameters
        const url = new URL(window.location.href);
    url.searchParams.set('review_page', page);
    if (rating > 0) {
        url.searchParams.set('rating', rating);
    }

        // Use fetch to get new reviews
    fetch(url)
        .then(response => response.text())
        .then(html => {
            // Create a temporary container
            const temp = document.createElement('div');
            temp.innerHTML = html;

            // Find the reviews container in the response
            const newReviews = temp.querySelector('.reviews-container');

            // Replace the current reviews with new ones
            if (newReviews) {
                reviewsSection.innerHTML = newReviews.innerHTML;
            }

            // Update URL without page reload
            window.history.pushState({}, '', url);

            // Scroll to the reviews section
            document.getElementById('reviews-section').scrollIntoView({ behavior: 'smooth' });
        });
}

document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            // Load first page with selected rating
            const rating = button.dataset.rating;
            loadReviewPage(1, rating === 'all' ? 0 : rating);
        });
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const submissionChilies = document.querySelectorAll('.submission-chilies .chili');
    const ratingInput = document.getElementById('rating');

    submissionChilies.forEach((chili, index) => {
        chili.addEventListener('mouseover', () => {
            submissionChilies.forEach((c, i) => {
                c.style.color = i <= index ? '#ff0000' : '#c2bdbd';
            });
        });

        chili.addEventListener('mouseout', () => {
            submissionChilies.forEach((c, i) => {
                c.style.color = i < ratingInput.value ? '#ff0000' : '#c2bdbd';
            });
        });

        chili.addEventListener('click', () => {
            ratingInput.value = index + 1;
            submissionChilies.forEach((c, i) => {
                c.classList.toggle('active', i <= index);
            });
        });
    });
});
</script>

</body>
</html>

<script>
function loadReviewPage(page, rating = 0) {
    const reviewsSection = document.querySelector('.reviews-container');
    const url = new URL(window.location.href);

    // Update query parameters
    url.searchParams.set('review_page', page);
    if (rating > 0) {
        url.searchParams.set('rating', rating);
    } else {
        url.searchParams.delete('rating');
    }

    // Fetch reviews dynamically
    fetch(url)
        .then((response) => response.text())
        .then((html) => {
            const temp = document.createElement('div');
            temp.innerHTML = html;

            const newReviews = temp.querySelector('.reviews-container');
            if (newReviews) {
                reviewsSection.innerHTML = newReviews.innerHTML;
            }

            // Update the browser URL without reload
            window.history.pushState({}, '', url);

            // Smooth scroll to reviews section
            document.getElementById('review-section').scrollIntoView({ behavior: 'smooth' });
        })
        .catch((error) => console.error('Error loading reviews:', error));
}

document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('.filter-btn');

    filterButtons.forEach((button) => {
        button.addEventListener('click', () => {
            // Update active button
            filterButtons.forEach((btn) => btn.classList.remove('active'));
            button.classList.add('active');

            // Load reviews for selected rating
            const rating = button.dataset.rating;
            loadReviewPage(1, rating === 'all' ? 0 : parseInt(rating, 10));
        });
    });
});
</script>
