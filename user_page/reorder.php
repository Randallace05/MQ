<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include '../conn/conn.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "
    <script>
        alert('You must log in to reorder.');
        window.location.href = '../index.php';
    </script>";
    exit;
}

// Get the logged-in user's ID
$tbl_user_id = intval($_SESSION['tbl_user_id']);

// Check if order ID is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);

    // Fetch cart items from the transaction history
    $query = "SELECT cart_items FROM transaction_history WHERE id = ? AND tbl_user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $order_id, $tbl_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $cart_items = json_decode($row['cart_items'], true); // Decode JSON cart items

        if (is_array($cart_items)) {
            // Add each item to the current cart
            foreach ($cart_items as $item) {
                $product_id = intval($item['product_id']);
                $quantity = intval($item['quantity']);

                // Check if the product already exists in the cart
                $check_cart_query = "SELECT quantity FROM cart WHERE tbl_user_id = ? AND product_id = ?";
                $stmt_check_cart = $conn->prepare($check_cart_query);
                $stmt_check_cart->bind_param('ii', $tbl_user_id, $product_id);
                $stmt_check_cart->execute();
                $cart_result = $stmt_check_cart->get_result();

                if ($cart_result->num_rows > 0) {
                    // Update quantity if the product already exists in the cart
                    $update_cart_query = "UPDATE cart SET quantity = quantity + ? WHERE tbl_user_id = ? AND product_id = ?";
                    $stmt_update_cart = $conn->prepare($update_cart_query);
                    $stmt_update_cart->bind_param('iii', $quantity, $tbl_user_id, $product_id);
                    $stmt_update_cart->execute();
                    $stmt_update_cart->close();
                } else {
                    // Insert a new row if the product does not exist in the cart
                    $insert_cart_query = "INSERT INTO cart (tbl_user_id, product_id, quantity) VALUES (?, ?, ?)";
                    $stmt_insert_cart = $conn->prepare($insert_cart_query);
                    $stmt_insert_cart->bind_param('iii', $tbl_user_id, $product_id, $quantity);
                    $stmt_insert_cart->execute();
                    $stmt_insert_cart->close();
                }

                $stmt_check_cart->close();
            }

            echo "<script>alert('Products have been added to your cart successfully.'); window.location.href = 'cart.php';</script>";
        } else {
            echo "<script>alert('No valid items found to reorder.'); window.location.href = 'orders.php';</script>";
        }
    } else {
        echo "<script>alert('Order not found.'); window.location.href = 'orders.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid request.'); window.location.href = 'orders.php';</script>";
}

// Close the database connection
$conn->close();
?>
