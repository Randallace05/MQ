<?php
require_once '../endpoint/session_config.php';
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
        .carousel-item img {
            height: 400px;
            object-fit: cover;
            width: 100%;
        }

        .promo-container {
            display: flex;
            gap: 5px;
            padding: 20px;
            justify-content: center;
        }

        .promo-image {
            width: calc(50% - 2.5px);
            height: 250px;
            object-fit: cover;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .product-card {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .product-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .wishlist-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: white;
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .wishlist-btn i {
            color: #000;
            transition: color 0.3s ease;
        }

        .wishlist-btn.active i {
            color: red;
        }

        .section-title {
            padding: 20px;
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        hr {
            margin: 0;
            border-top: 2px solid #ddd;
        }
        
        .dot {
        cursor: pointer;
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbb;
        border-radius: 50%;
        display: inline-block;
        transition: background-color 0.6s ease;
        }

    </style>
</head>
<body>
    <?php include("../includes/topbar1.php"); ?>
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


    <hr>

    <!-- Most Popular Products -->
    <h2 class="section-title">Most Popular Bagoong</h2>
    <div class="product-grid">
        <?php
        include '../conn/conn.php';

        // Query to fetch top 3 most ordered products
        // $sql = "SELECT p.id, p.name, p.price, p.image, COUNT(o.product_id) as order_count 
        //         FROM products p 
        //         LEFT JOIN orders o ON p.id = o.product_id 
        //         WHERE p.is_disabled = 0 
        //         GROUP BY p.id 
        //         ORDER BY order_count DESC 
        //         LIMIT 3";

        $sql = "SELECT id, name, price, image FROM products WHERE is_disabled = 0";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($product = $result->fetch_assoc()) {
                ?>
                <div class="product-card">
                    <button class="wishlist-btn" onclick="toggleWishlist(this, <?php echo $product['id']; ?>)">
                        <i class="bi bi-heart"></i>
                    </button>
                    <a href="items.php?id=<?php echo $product['id']; ?>">
                        <img src="../admin_page/foodMenu/uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             class="product-image">
                    </a>
                    <div class="p-3">
                        <h5 class="text-center mb-2"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="text-center mb-2">₱<?php echo number_format($product['price'], 2); ?></p>
                        <div class="chili-rating" data-product-id="<?php echo $product['id']; ?>">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <span class="chili" data-value="<?php echo $i; ?>">🌶️</span>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>

    <h2 class="section-title">Our Products</h2>
    <div class="product-grid">
        <?php
        // Query to fetch all other products
        // $sql = "SELECT p.* FROM products p 
        //         WHERE p.is_disabled = 0 
        //         AND p.id NOT IN (
        //             SELECT p2.id FROM products p2 
        //             LEFT JOIN orders o ON p2.id = o.product_id 
        //             GROUP BY p2.id 
        //             ORDER BY COUNT(o.product_id) DESC 
        //             LIMIT 3
        //         )";


        $sql = "SELECT id, name, price, image FROM products WHERE is_disabled = 0";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($product = $result->fetch_assoc()) {
                ?>
                <div class="product-card">
                    <button class="wishlist-btn" onclick="toggleWishlist(this, <?php echo $product['id']; ?>)">
                        <i class="bi bi-heart"></i>
                    </button>
                    <a href="items.php?id=<?php echo $product['id']; ?>">
                        <img src="../admin_page/foodMenu/uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             class="product-image">
                    </a>
                    <div class="p-3">
                        <h5 class="text-center mb-2"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="text-center mb-2">₱<?php echo number_format($product['price'], 2); ?></p>
                        <div class="chili-rating" data-product-id="<?php echo $product['id']; ?>">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <span class="chili" data-value="<?php echo $i; ?>">🌶️</span>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>

    <?php include("../includes/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleWishlist(button, productId) {
            button.classList.toggle('active');
            const icon = button.querySelector('i');
            icon.classList.toggle('bi-heart');
            icon.classList.toggle('bi-heart-fill');
            
            // Here you can add AJAX call to update wishlist in database
            fetch('update_wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    product_id: productId,
                    action: button.classList.contains('active') ? 'add' : 'remove'
                })
            });
        }

        // Initialize chili ratings
        document.querySelectorAll('.chili-rating').forEach(container => {
            const chilies = container.querySelectorAll('.chili');
            let selectedRating = 0;

            chilies.forEach(chili => {
                chili.addEventListener('mouseover', () => {
                    if (selectedRating === 0) {
                        resetChilies(container);
                        highlightChilies(container, chili.dataset.value);
                    }
                });

                chili.addEventListener('click', () => {
                    selectedRating = chili.dataset.value;
                    highlightChilies(container, selectedRating);
                });

                chili.addEventListener('mouseout', () => {
                    if (selectedRating === 0) {
                        resetChilies(container);
                    }
                });
            });
        });

        function highlightChilies(container, rating) {
            const chilies = container.querySelectorAll('.chili');
            chilies.forEach((chili, index) => {
                if (index < rating) {
                    chili.classList.add('selected');
                } else {
                    chili.classList.remove('selected');
                }
            });
        }

        function resetChilies(container) {
            container.querySelectorAll('.chili').forEach(chili => {
                chili.classList.remove('selected');
            });
        }

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

</body>
</html>