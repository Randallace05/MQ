<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include '../conn/conn.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized action.']);
    exit;
}

$tbl_user_id = intval($_SESSION['tbl_user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id'], $_POST['quantity'])) {
    $cart_id = intval($_POST['cart_id']);
    $quantity = intval($_POST['quantity']);

    // Validate cart item belongs to user
    $check_query = $conn->prepare("SELECT * FROM `cart` WHERE cart_id = ? AND tbl_user_id = ?");
    $check_query->bind_param("ii", $cart_id, $tbl_user_id);
    $check_query->execute();
    $result = $check_query->get_result();

    if ($result->num_rows > 0) {
        $cart_item = $result->fetch_assoc();
        $product_id = $cart_item['product_id'];
        $current_quantity = $cart_item['quantity'];

        // Check product stock
        $stock_query = $conn->prepare("SELECT stock FROM `products` WHERE id = ?");
        $stock_query->bind_param("i", $product_id);
        $stock_query->execute();
        $stock_result = $stock_query->get_result();

        if ($stock_result->num_rows > 0) {
            $product = $stock_result->fetch_assoc();
            $available_stock = $product['stock'];

            if ($quantity > $available_stock) {
                echo json_encode(['status' => 'error', 'message' => 'Insufficient stock.']);
                exit;
            }

            // Update cart quantity and adjust stock
            $quantity_difference = $quantity - $current_quantity;
            $new_stock = $available_stock - $quantity_difference;

            $conn->begin_transaction();
            try {
                $update_cart = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE cart_id = ?");
                $update_cart->bind_param("ii", $quantity, $cart_id);
                $update_cart->execute();

                $update_stock = $conn->prepare("UPDATE `products` SET stock = ? WHERE id = ?");
                $update_stock->bind_param("ii", $new_stock, $product_id);
                $update_stock->execute();

                $conn->commit();
                echo json_encode(['status' => 'success', 'message' => 'Quantity updated successfully.']);
            } catch (Exception $e) {
                $conn->rollback();
                echo json_encode(['status' => 'error', 'message' => 'Error updating quantity.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Product not found.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Cart item not found.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}

?>
<script>
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
</script>