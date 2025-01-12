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
        alert('You must log in to remove items.');
        window.location.href = '../index.php'; // Redirect to the login page
    </script>";
    exit;
}

// Get the cart item ID from the request
if (isset($_GET['id'])) {
    $cart_id = intval($_GET['id']);

    // Begin a database transaction
    $conn->begin_transaction();

    try {
        // Retrieve product ID and quantity from the cart
        $cartQuery = $conn->prepare("SELECT product_id, quantity FROM cart WHERE cart_id = ?");
        $cartQuery->bind_param("i", $cart_id);
        $cartQuery->execute();
        $cartResult = $cartQuery->get_result();

        if ($cartResult->num_rows > 0) {
            $cartItem = $cartResult->fetch_assoc();
            $product_id = intval($cartItem['product_id']);
            $quantity = intval($cartItem['quantity']);

            // Restore the stock of the product
            $updateStockQuery = $conn->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
            $updateStockQuery->bind_param("ii", $quantity, $product_id);
            $updateStockQuery->execute();

            // Remove the item from the cart
            $deleteCartQuery = $conn->prepare("DELETE FROM cart WHERE cart_id = ?");
            $deleteCartQuery->bind_param("i", $cart_id);
            $deleteCartQuery->execute();

            // Commit the transaction
            $conn->commit();

            $_SESSION['success_message'] = "Item removed and stock updated successfully.";
        } else {
            $_SESSION['error_message'] = "Cart item not found.";
        }

        $cartQuery->close();
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $conn->rollback();
        $_SESSION['error_message'] = "An error occurred: " . $e->getMessage();
    }

    // Redirect back to the cart page
    header("Location: cart.php");
    exit;
} else {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: cart.php");
    exit;
}
?>
