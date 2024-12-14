<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php"); // Redirect to login page
    exit;
}

// Include database connection
include '../conn/conn.php'; // Replace with your actual database connection file

// Check if the action and wishlist ID are set
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'remove' && isset($_POST['wishlist_id'])) {
        $wishlist_id = intval($_POST['wishlist_id']); // Sanitize wishlist ID

        // SQL to delete the item from the wishlist
        $deleteSql = "DELETE FROM wishlist WHERE wish_id = ?";
        $stmt = $conn->prepare($deleteSql);
        $stmt->bind_param("i", $wishlist_id);

        if ($stmt->execute()) {
            // Success: Redirect back to wishlist page
            $_SESSION['success_message'] = "Item successfully removed from your wishlist.";
        } else {
            // Failure: Redirect back with error message
            $_SESSION['error_message'] = "Error removing item. Please try again.";
        }

        $stmt->close();
        header("Location: wishlist.php"); // Redirect back to the wishlist page
        exit;
    }
}

// If invalid access, redirect back
header("Location: wishlist.php");
exit;
?>
