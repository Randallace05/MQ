<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "<script>
        alert('You must log in to access the wishlist.');
        window.location.href = '../index.php';
    </script>";
    exit;
}

include("../includes/topbar1.php");
include '../conn/conn.php';

$tbl_user_id = intval($_SESSION['tbl_user_id']);

// Fetch wishlist items for the user
$sql = "SELECT wish_id, w.product_id, p.name AS product_name, p.price, p.image AS product_image
        FROM wishlist w
        JOIN products p ON w.product_id = p.id
        WHERE w.tbl_user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tbl_user_id);
$stmt->execute();
$wishlistResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;

        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .cart-header {
            display: grid;
            grid-template-columns: 1fr 150px 150px;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
            margin-bottom: 2rem;
        }

        .cart-header span {
            font-weight: 500;
            color: #333;
        }

        .cart-item {
            display: grid;
            grid-template-columns: 1fr 150px 150px;
            align-items: center;
            padding: 2rem 0;
            border-bottom: 1px solid #eee;
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }

        .product-name {
            font-weight: 500;
            color: #333;
        }

        .price {
            color: #333;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .remove-link {
            color: #666;
            text-decoration: none;
            font-size: 0.875rem;
        }

        .remove-link:hover {
            text-decoration: underline;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: 1px solid #333;
            background: white;
            color: #333;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.875rem;
            min-width: 120px;
            text-align: center;
        }

        .btn:hover {
            background: #f8f8f8;
        }

        .return-section {
            margin-top: 2rem;  
        }

        .empty-message {
            text-align: center;
            font-size: 1.2rem;
            color: #777;
            margin-top: 2rem;
        }

        .containerr {
            max-width: 1200px;
            margin: 0 auto;
        }
        .containerr {
            justify-content: center;
            align-items: center;
            padding: 40px;
        }
    </style>
</head>
<body>
    <div class="containerr">
        <?php
        if (isset($_SESSION['success_message'])) {
            echo "<div style='color: green; text-align: center; margin-bottom: 10px;'>".$_SESSION['success_message']."</div>";
            unset($_SESSION['success_message']);
        }

        if (isset($_SESSION['error_message'])) {
            echo "<div style='color: red; text-align: center; margin-bottom: 10px;'>".$_SESSION['error_message']."</div>";
            unset($_SESSION['error_message']);
        }
        ?>

        <h1 style="text-align: center; color: #333;">My Wishlist</h1>

        <?php if ($wishlistResult->num_rows > 0): ?>
            <div class="cart-header">
                <span>Product</span>
                <span>Price</span>
                <span>Actions</span>
            </div>

            <?php while ($row = $wishlistResult->fetch_assoc()): ?>
                <div class="cart-item">
                    <div class="product-info">
                        <img src="<?php
                            echo file_exists("../admin_page/foodMenu/uploads/".$row['product_image'])
                                ? "../admin_page/foodMenu/uploads/".htmlspecialchars($row['product_image'])
                                : 'default_image.jpg';
                        ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>" class="product-image">
                        <span class="product-name"><?php echo htmlspecialchars($row['product_name']); ?></span>
                    </div>
                    <div class="price">₱<?php echo number_format($row['price'], 2); ?></div>
                    <div class="actions">
                        <form action="wishlist_action.php" method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="wishlist_id" value="<?php echo $row['wish_id']; ?>">
                            <button type="submit" class="remove-link">Remove</button>
                        </form>
                        <form action="cartItems.php" method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="add_to_cart">
                            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                            <button type="submit" class="btn">Add To Cart</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="empty-message">Your wishlist is currently empty. Start adding items to your wishlist now!</p>
        <?php endif; ?>

        <div class="return-section">
            <a href="shop.php" class="btn">Return To Shop</a>
        </div>
    </div>
</body>
</html>

