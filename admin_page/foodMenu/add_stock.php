<?php
include '../../conn/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the product ID and stock details from the form
    $product_id = $_POST['product_id'];
    $stock = $_POST['stock'];
    $expiration_date = $_POST['expiration_date'];
    $code_name = $_POST['code_name'];

    if (!empty($expiration_date)) {
        $expiration_date .= '-01'; // Append the day (e.g., 'YYYY-MM' to 'YYYY-MM-01')
    } else {
        $expiration_date = null; // Set to NULL if the expiration date is empty
    }

    // Insert the new stock batch into the product_stocks table
    $sql = "INSERT INTO product_stocks (product_id, stock, expiration_date, code_name) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiss', $product_id, $stock, $expiration_date, $code_name);

    if ($stmt->execute()) {
        // Successfully added the new stock batch
        header("Location: foodMenu.php?id=" . $product_id); // Redirect back to the product details page
    } else {
        // Handle error (if any)
        echo "Error: " . $stmt->error;
    }
}
?>
