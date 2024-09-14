<?php
include '../../conn/conn.php'; 

$id = $_GET['id'];

// Fetch the current 'is_disabled' status of the product
$sql = "SELECT is_disabled FROM products WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die('Error: Product not found.');
}

// Toggle the 'is_disabled' status (if 1, set to 0; if 0, set to 1)
$new_status = $product['is_disabled'] == 1 ? 0 : 1;

// Update the product's 'is_disabled' status
$sql = "UPDATE products SET is_disabled = :new_status WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([
    ':new_status' => $new_status,
    ':id' => $id
]);

$status = $new_status == 1 ? "disabled" : "enabled";
echo "Product {$status} successfully!";
?>
