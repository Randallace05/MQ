<?php
include '../../conn/conn.php';

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];

    $sql = "UPDATE products SET name = :name, price = :price, description = :description, stock = :stock WHERE id = :id";
    
    $stmt = $conn->prepare($sql); 
    $stmt->execute([
        ':name' => $name,
        ':price' => $price,
        ':description' => $description,
        ':stock' => $stock,
        ':id' => $id
    ]);

    // Use JavaScript for the alert and redirect
    echo "<script>
        alert('Product updated successfully!');
        window.location.href = 'FoodMenu.php';
    </script>";
    exit(); // Ensure no further code is executed
}

// Fetch the product data
$sql = "SELECT * FROM products WHERE id = :id";
$stmt = $conn->prepare($sql); 
$stmt->execute([':id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
?>
