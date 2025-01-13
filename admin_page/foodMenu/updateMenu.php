<?php
include '../../conn/conn.php';

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];
    $expiration_date = $_POST['expiration_date']; // Get the expiration date from the form

    // Validate expiration_date
    if (!empty($expiration_date)) {
        $expiration_date .= '-01'; // Append the day (e.g., 'YYYY-MM' to 'YYYY-MM-01')
    } else {
        $expiration_date = null; // Set to NULL if the expiration date is empty
    }

    // Update product query
    $sql = "UPDATE products SET name = ?, price = ?, description = ?, stock = ?, expiration_date = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sdsssi', $name, $price, $description, $stock, $expiration_date, $id);

    if ($stmt->execute()) {
        // JavaScript for the alert and redirect
        echo "<script>
            alert('Product updated successfully!');
            window.location.href = 'FoodMenu.php';
        </script>";
    } else {
        echo "Error updating product: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit(); // Ensure no further code is executed
}

// Fetch the product data
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>
