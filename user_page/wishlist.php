<?php
session_start(); // Start the session

// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "
    <script>
        alert('You must log in to access the wishlist.');
        window.location.href = '../index.php'; // Redirect to login page
    </script>
    ";
    exit;
}

// Include database connection
include '../conn/conn.php'; // Replace with your actual database connection file

// Get logged-in user's ID
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

<?php
// Display success or error messages
if (isset($_SESSION['success_message'])) {
    echo "<div style='color: green; text-align: center; margin-bottom: 10px;'>".$_SESSION['success_message']."</div>";
    unset($_SESSION['success_message']); // Clear the message
}

if (isset($_SESSION['error_message'])) {
    echo "<div style='color: red; text-align: center; margin-bottom: 10px;'>".$_SESSION['error_message']."</div>";
    unset($_SESSION['error_message']); // Clear the message
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .wishlist-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .wishlist-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .wishlist-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .wishlist-table th, .wishlist-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .wishlist-table th {
            background-color: #f4f4f4;
        }
        .wishlist-table .actions {
            text-align: center;
        }
        .button {
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #45a049;
        }
        .button.danger {
            background-color: #f44336;
        }
        .button.danger:hover {
            background-color: #e53935;
        }
        .product-image {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="wishlist-container">
        <div class="wishlist-header">
            <h1>My Wishlist</h1>
            <p>Here are the items you wish to purchase later:</p>
        </div>

        <!-- Back to Shop Button -->
        <div style="text-align: center; margin-bottom: 20px;">
            <a href="shop.php" class="button">Back to Shop</a>
        </div>

        <?php if ($wishlistResult->num_rows > 0): ?>
            <table class="wishlist-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $wishlistResult->fetch_assoc()): ?>
                    <tr>
                        <td>
                        <img src="<?php
                                    echo file_exists("../admin_page/foodMenu/uploads/".$row['product_image'])
                                    ? "../admin_page/foodMenu/uploads/".htmlspecialchars($row['product_image'])
                                    : 'default_image.jpg';
                                ?>"
                                alt="Product Image"
                                style="width: 200px; height: 200px; object-fit: cover;">
                        </td>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td>₱<?php echo number_format($row['price'], 2); ?></td>
                        <td class="actions">
                            <form action="wishlist_action.php" method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="wishlist_id" value="<?php echo $row['wish_id']; ?>">
                                <button type="submit" class="button danger">Remove</button>
                            </form>
                            <form action="cartItems.php" method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="add_to_cart">
                                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                <button type="submit" class="button">Add to Cart</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Your wishlist is currently empty. Start adding items to your wishlist now!</p>
        <?php endif; ?>
    </div>
</body>
</html>
