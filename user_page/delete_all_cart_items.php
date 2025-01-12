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

// Get the logged-in user's ID securely from the session
$tbl_user_id = intval($_SESSION['tbl_user_id']);

// Begin a database transaction
$conn->begin_transaction();

try {
    // Retrieve all cart items for the user
    $cartQuery = $conn->prepare("SELECT product_id, quantity FROM cart WHERE tbl_user_id = ?");
    $cartQuery->bind_param("i", $tbl_user_id);
    $cartQuery->execute();
    $cartResult = $cartQuery->get_result();

    if ($cartResult->num_rows > 0) {
        // Loop through each cart item and restore the stock
        while ($cartItem = $cartResult->fetch_assoc()) {
            $product_id = intval($cartItem['product_id']);
            $quantity = intval($cartItem['quantity']);

            // Update stock for each product
            $updateStockQuery = $conn->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
            $updateStockQuery->bind_param("ii", $quantity, $product_id);
            $updateStockQuery->execute();
        }

        // Remove all items from the cart for the user
        $deleteCartQuery = $conn->prepare("DELETE FROM cart WHERE tbl_user_id = ?");
        $deleteCartQuery->bind_param("i", $tbl_user_id);
        $deleteCartQuery->execute();

        // Commit the transaction
        $conn->commit();

        $_SESSION['success_message'] = "All items removed and stock updated successfully.";
    } else {
        $_SESSION['error_message'] = "No items found in the cart.";
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
?>
