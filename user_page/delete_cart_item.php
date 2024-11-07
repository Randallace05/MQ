<?php
include '../conn/conn.php'; // Ensure database connection is included

if (isset($_GET['id'])) {
    $cart_id = intval($_GET['id']); // Sanitize input

    // Prepare and execute deletion query
    $delete_query = $conn->prepare("DELETE FROM `cart` WHERE cart_id = :cart_id");
    $delete_query->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
    
    if ($delete_query->execute()) {
        // Optionally redirect or show a success message
        header("Location: cart.php");
        exit;
    } else {
        echo "Failed to remove item from the cart.";
    }
} else {
    echo "Invalid request.";
}
?>
