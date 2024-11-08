<?php
include '../conn/conn.php'; // Include database connection

// Delete all products from the cart
$delete_all = $conn->prepare("DELETE FROM `cart`");
$delete_all->execute();

// Redirect back to the cart page with a success message
header("Location: cartItems.php?message=All+products+removed+from+cart");
exit();
