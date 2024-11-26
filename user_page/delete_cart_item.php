<?php
include '../conn/conn.php'; // Include the MySQLi connection file

if (isset($_GET['id'])) {
    // Sanitize the input to ensure it is treated as an integer
    $cart_id = intval($_GET['id']);

    // Prepare the DELETE query
    $delete_query = $conn->prepare("DELETE FROM `cart` WHERE `cart_id` = ?");
    if ($delete_query) {
        // Bind the parameter (i = integer)
        $delete_query->bind_param("i", $cart_id);

        // Execute the query
        if ($delete_query->execute()) {
            // Redirect to the cart page if successful
            header("Location: cart.php");
            exit;
        } else {
            // Output an error message if the execution fails
            echo "Error: Could not delete the item from the cart.";
        }

        // Close the prepared statement
        $delete_query->close();
    } else {
        // Output an error if the query preparation fails
        echo "Error: Unable to prepare the SQL statement.";
    }
} else {
    // Output a message if 'id' is not provided in the URL
    echo "Invalid request.";
}

// Close the database connection
$conn->close();
?>
