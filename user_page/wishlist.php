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
include("../includes/topbar1.php");
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
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .wishlist-container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .wishlist-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .wishlist-header h1 {
            font-size: 2rem;
            color: #4CAF50;
            margin-bottom: 10px;
        }

        .wishlist-header p {
            color: #666;
        }

        .wishlist-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .wishlist-table th, .wishlist-table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .wishlist-table th {
            background-color: #f4f4f4;
            font-weight: bold;
            color: #333;
        }

        .wishlist-table tr:hover {
            background-color: #f7f7f7;
        }

        .product-image {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }

        .actions button {
            margin: 5px;
            padding: 10px 15px;
            font-size: 0.9rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .actions .button {
            background-color: #4CAF50;
            color: white;
        }

        .actions .button:hover {
            background-color: #45a049;
        }

        .actions .danger {
            background-color: #f44336;
            color: white;
        }

        .actions .danger:hover {
            background-color: #e53935;
        }

        .back-to-shop {
            display: inline-block;
            margin-bottom: 20px;
            text-align: center;
        }

        .back-to-shop a {
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .back-to-shop a:hover {
            background-color: #45a049;
        }

        .empty-message {
            text-align: center;
            font-size: 1.2rem;
            color: #777;
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
        <div class="back-to-shop">
            <a href="shop.php">&larr; Back to Shop</a>
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
                                class="product-image">
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
            <p class="empty-message">Your wishlist is currently empty. Start adding items to your wishlist now!</p>
        <?php endif; ?>
    </div>
</body>
</html>
