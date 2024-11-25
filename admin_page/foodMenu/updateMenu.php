<?php
include '../../conn/conn.php';

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];

    // Update product query
    $sql = "UPDATE products SET name = ?, price = ?, description = ?, stock = ? WHERE id = ?";
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param('sdssi', $name, $price, $description, $stock, $id);

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
