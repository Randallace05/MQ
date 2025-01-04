<?php
// Include database connection
include '../conn/conn.php';

// Start session and check login
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "
    <script>
        alert('You must log in to perform this action.');
        window.location.href = '../index.php'; // Redirect to the login page
    </script>
    ";
    exit;
}

// Get the logged-in user's ID
$tbl_user_id = intval($_SESSION['tbl_user_id']); // Assuming `tbl_user_id` is securely stored in the session

// Delete related records from the checkout table first
$delete_checkout_query = $conn->prepare("
    DELETE FROM `checkout`
    WHERE `cart` IN (SELECT `cart_id` FROM `cart` WHERE `tbl_user_id` = ?)
");
$delete_checkout_query->bind_param("i", $tbl_user_id);
$delete_checkout_query->execute();

// Check if the deletion from the checkout table was successful
if ($delete_checkout_query->affected_rows >= 0) {
    // Now delete all items from the cart for the logged-in user
    $delete_cart_query = $conn->prepare("DELETE FROM `cart` WHERE `tbl_user_id` = ?");
    $delete_cart_query->bind_param("i", $tbl_user_id);

    if ($delete_cart_query->execute()) {
        echo "
        <script>
            alert('All items have been removed from your cart.');
            window.location.href = 'cart.php'; // Redirect to the cart page
        </script>
        ";
    } else {
        echo "
        <script>
            alert('Failed to delete items from your cart. Please try again.');
            window.location.href = 'cart.php'; // Redirect to the cart page
        </script>
        ";
    }
} else {
    echo "
    <script>
        alert('Failed to clear related records. Please try again.');
        window.location.href = 'cart.php'; // Redirect to the cart page
    </script>
    ";
}

$conn->close(); // Close the database connection
?>
