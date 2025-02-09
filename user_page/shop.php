<?php
require_once '../endpoint/session_config.php';


// Fetch user role based on session or user ID (adjust logic as needed)
$user_id = $_SESSION['tbl_user_Id'] ?? null; // Replace with your actual session variable
$user_role = '';

if ($user_id) {
    $sql = "SELECT user_role FROM tbl_user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tbl_user_id);
    $stmt->execute();
    $stmt->bind_result($user_role);
    $stmt->fetch();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>MQ Kitchen</title>
    <link rel="icon" type="image/x-icon" href="../user_page/assets/sili.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
            animation: fadeIn 1s ease-in-out;
        }

        .carousel-item img {
            height: 400px;
            object-fit: cover;
            width: 100%;
            border-radius: 10px;
        }

        .promo-container {
            display: flex;
            gap: 10px;
            padding: 20px;
            justify-content: center;
        }

        .promo-image {
            width: calc(50% - 5px);
            height: 250px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .promo-image:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px 0;
        }

        .product-card {
            position: relative;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            background-color: #fff;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
            transition: transform 0.3s;
        }

        .product-card:hover .product-image {
            transform: scale(1.1);
        }

        .wishlist-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, transform 0.3s;
        }

        .wishlist-btn:hover {
            background-color: #f8d7da;
            transform: scale(1.1);
        }

        .wishlist-btn.active i {
            color: #ff0000;
        }

        .section-title {
            padding: 20px;
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::before {
            content: '';
            display: inline-block;
            width: 15px;
            height: 15px;
            background-color: #ff0000;
            border-radius: 50%;
        }

        .section-title .highlight {
            color: #ff0000;
        }

        .product-info {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            padding: 15px;
        }

        .chili-rating {
            margin-top: auto;
            text-align: center;
            padding: 10px 0;
        }

        .chili-rating .total-ratings {
            font-size: 0.8em;
            color: #666;
            margin-left: 5px;
        }

        .chili-rating .average-rating {
            font-weight: bold;
            margin-left: 5px;
        }

        .chili-rating .chili {
            opacity: 0.3;
            transition: opacity 0.3s;
        }

        .chili-rating .chili.filled {
            opacity: 1;
            color: #ff6347;
        }

        .carousel-dots {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .dot {
            cursor: pointer;
            height: 15px;
            width: 15px;
            margin: 0 5px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .dot.active {
            background-color: #ff6347;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 1200px) {
            .product-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .main-container {
                padding: 0 20px;
            }

            .promo-container {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .product-grid {
                grid-template-columns: 1fr;
            }
        }
        .modal-content {
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            background-color: #ff6347;
            color: white;
            border-bottom: none;
            border-radius: 15px 15px 0 0;
        }

        .modal-footer {
            border-top: none;
        }
    </style>
</head>
<body>
    <?php include("../includes/topbar1.php"); ?>

    <div class="main-container">
        <!-- Carousel -->
        <div class="carousel-container">
            <div class="carousel-inner">
                <?php
                include '../conn/conn.php';

                // Fetch carousel images from the database
                $sql = "SELECT image_path FROM carousel_images LIMIT 6"; // Limit to a max of 6 images
                $result = $conn->query($sql);
                $dotCount = 0;

                if ($result && $result->num_rows > 0) {
                    $first = true; // Flag to track the first iteration
                    while ($row = $result->fetch_assoc()) {
                        $imagePath = htmlspecialchars($row['image_path']); // Sanitize output
                        $fullPath = "../admin_page/foodMenu/" . $imagePath; // Adjust path

                        // Display image only if the file exists
                        if (file_exists($fullPath)) {
                            echo '<div class="carousel-item ' . ($first ? 'active' : '') . '">
                                <img src="' . $fullPath . '" class="d-block w-100" alt="Carousel Image">
                            </div>';
                            $first = false; // Set the flag to false after the first iteration
                            $dotCount++; // Increment the dot count for each valid image
                        }
                    }
                } else {
                    echo '<div class="carousel-item active">
                        <img src="uploads/default.jpg" class="d-block w-100" alt="Default Image">
                        <p>No images found in the database.</p>
                    </div>';
                    $dotCount = 1; // Ensure at least one dot for the default image
                }
                ?>
            </div>

            <div class="carousel-dots" style="text-align:center; margin-top: 10px;">
                <?php
                // Render dots based on the number of images
                for ($i = 0; $i < $dotCount; $i++) {
                    echo '<span class="dot" data-slide="' . $i . '"></span>';
                }
                ?>
            </div>
        </div>

        <!-- Fetch and Display Promotional Images -->
        <div class="promo-container">
            <?php
            include '../conn/conn.php';

            // Define the directory path where images are stored
            $imageDir = "../admin_page/foodMenu/";

            // Fetch left and right promotional image paths from the database
            $promoSql = "SELECT left_image_path, right_image_path FROM carousel_images WHERE id = 1";
            $promoResult = $conn->query($promoSql);

            // Initialize variables for left and right promotional images
            $leftPromotionImage = "assets/default_left.jpg";  // Default image
            $rightPromotionImage = "assets/default_right.jpg"; // Default image

            if ($promoResult && $promoResult->num_rows > 0) {
                $promoRow = $promoResult->fetch_assoc();
                $leftPromotionImage = $promoRow['left_image_path'] ? htmlspecialchars($promoRow['left_image_path']) : "assets/default_left.jpg";
                $rightPromotionImage = $promoRow['right_image_path'] ? htmlspecialchars($promoRow['right_image_path']) : "assets/default_right.jpg";
            }

            // Generate full file paths
            $leftFullPath = $imageDir . $leftPromotionImage; // Full path for left image
            $rightFullPath = $imageDir . $rightPromotionImage; // Full path for right image

            // Debugging: Check the generated file paths
            echo "<!-- Left Path: $leftFullPath -->";
            echo "<!-- Right Path: $rightFullPath -->";

            // Check if the file exists on the server, fallback to default if not
            $leftFullPath = file_exists($leftFullPath) ? $leftFullPath : "assets/default_left.jpg";
            $rightFullPath = file_exists($rightFullPath) ? $rightFullPath : "assets/default_right.jpg";

            // Display Left Image (Promotion 1)
            echo '<img src="' . $leftFullPath . '" alt="Promotion 1" class="promo-image">';

            // Display Right Image (Promotion 2)
            echo '<img src="' . $rightFullPath . '" alt="Promotion 2" class="promo-image">';
            ?>
        </div>
    </div>

    <hr>

    <div class="main-container">
        <!-- Most Popular Products -->
        <h2 class="section-title">
            <span class="highlight">This Month</span>
            Most Popular Bagoong
        </h2>
        <div class="product-grid">
            <?php
            include '../conn/conn.php';

            $sql = "SELECT p.id, p.name, p.price, p.image,
               COUNT(r.rating) as total_ratings,
               AVG(r.rating) as average_rating
        FROM products p
        LEFT JOIN reviews r ON p.id = r.product_id
        WHERE p.is_disabled = 0
        GROUP BY p.id";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($product = $result->fetch_assoc()) {
                    ?>
                    <div class="product-card">
                        </button>
                        <a href="items.php?id=<?php echo $product['id']; ?>">
                            <img src="../admin_page/foodMenu/uploads/<?php echo htmlspecialchars($product['image']); ?>"
                                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                                 class="product-image">
                        </a>
                        <div class="product-info">
                            <div class="p-3">
                                <h5 class="text-center mb-2"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="text-center mb-2">₱<?php echo number_format($product['price'], 2); ?></p>
                            </div>
                            <div class="chili-rating" data-product-id="<?php echo $product['id']; ?>">
                                <?php
                                $average_rating = round($product['average_rating'], 1);
                                for($i = 1; $i <= 5; $i++):
                                ?>
                                    <span class="chili filled" data-value="<?php echo $i; ?>">
                                        <?php
                                        if ($i <= floor($average_rating)) {
                                            echo '⭐';
                                        } elseif ($i == ceil($average_rating) && $average_rating != floor($average_rating)) {
                                            echo '⭐';
                                        } else {
                                            echo '⭐';
                                        }
                                        ?>
                                    </span>
                                <?php endfor; ?>
                                <span class="average-rating"><?php echo number_format($average_rating, 1); ?></span>
                                <span class="total-ratings">(<?php echo $product['total_ratings']; ?>)</span>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>

        <h2 class="section-title">Explore
            <span class="highlight">Our Products</span>
        </h2>
        <div class="product-grid">
            <?php
            $sql = "SELECT p.id, p.name, p.price, p.image,
               COUNT(r.rating) as total_ratings,
               AVG(r.rating) as average_rating
                FROM products p
                LEFT JOIN reviews r ON p.id = r.product_id
                WHERE p.is_disabled = 0
                GROUP BY p.id";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($product = $result->fetch_assoc()) {
                    ?>
                    <div class="product-card">
                        </button>
                        <a href="items.php?id=<?php echo $product['id']; ?>">
                            <img src="../admin_page/foodMenu/uploads/<?php echo htmlspecialchars($product['image']); ?>"
                                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                                 class="product-image">
                        </a>
                        <div class="product-info">
                            <div class="p-3">
                                <h5 class="text-center mb-2"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="text-center mb-2">₱<?php echo number_format($product['price'], 2); ?></p>
                            </div>
                            <div class="chili-rating" data-product-id="<?php echo $product['id']; ?>">
                                <?php
                                $average_rating = round($product['average_rating'], 1);
                                for($i = 1; $i <= 5; $i++):
                                ?>
                                    <span class="chili filled" data-value="<?php echo $i; ?>">
                                        <?php
                                        if ($i <= floor($average_rating)) {
                                            echo '⭐';
                                        } elseif ($i == ceil($average_rating) && $average_rating != floor($average_rating)) {
                                            echo '⭐';
                                        } else {
                                            echo '⭐';
                                        }
                                        ?>
                                    </span>
                                <?php endfor; ?>
                                <span class="average-rating"><?php echo number_format($average_rating, 1); ?></span>
                                <span class="total-ratings">(<?php echo $product['total_ratings']; ?>)</span>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>

    <?php include("../includes/footer.php"); ?>
     <!-- Modal -->
     <div class="modal fade" id="reminderModal" tabindex="-1" aria-labelledby="reminderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reminderModalLabel">Join Our Team!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>If you want to become part of our Distributor/Reseller, please contact us on Facebook:</p>
                    <a href="https://www.facebook.com/mqkitchen.main" target="_blank" class="btn btn-primary">
                        <i class="bi bi-facebook"></i> MQ Kitchen Facebook Page
                    </a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleWishlist(button, productId) {
            const isActive = button.classList.contains('active');
            const action = isActive ? 'remove' : 'add';

            fetch('update_wishlist.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    product_id: productId,
                    action: action
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    button.classList.toggle('active');
                    const icon = button.querySelector('i');
                    icon.classList.toggle('bi-heart');
                    icon.classList.toggle('bi-heart-fill');

                    // Update the wishlist badge count in real time
                    document.querySelector('.icon-badge').textContent = data.wishlist_count;
                } else {
                    alert(data.message || 'Something went wrong.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating your wishlist.');
            });
        }


        // Initialize chili ratings
        document.querySelectorAll('.chili-rating').forEach(container => {
            const chilies = container.querySelectorAll('.chili');
            const averageRating = parseFloat(container.querySelector('.average-rating').textContent);

            chilies.forEach((chili, index) => {
                if (index < Math.floor(averageRating)) {
                    chili.classList.add('filled');
                } else if (index < averageRating) {
                    chili.classList.add('filled');
                    chili.style.opacity = '0.5';
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const dots = document.querySelectorAll('.dot');
            const slides = document.querySelectorAll('.carousel-item');

            let currentIndex = 0;

            function updateCarousel(index) {
                slides.forEach((slide, i) => {
                    slide.classList.toggle('active', i === index);
                });
                dots.forEach((dot, i) => {
                    dot.style.backgroundColor = i === index ? '#717171' : '#bbb';
                });
                currentIndex = index;
            }

            dots.forEach((dot, i) => {
                dot.addEventListener('click', () => {
                    updateCarousel(i);
                });
            });

            // Initialize the carousel
            if (dots.length > 0) {
                updateCarousel(0);
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Check the PHP variable for user role
            const userRole = "<?php echo $user_role; ?>";

            // Show the modal if the user role is 'customer'
            if (userRole === "customer") {
                const reminderModal = new bootstrap.Modal(document.getElementById('reminderModal'));
                reminderModal.show();
            }
        });
    </script>

</body>
</html>
