<?php
session_start();
include '../../conn/conn.php'; 

$id = $_GET['id'];

// Fetch the current 'is_disabled' status of the product
$sql = "SELECT is_disabled FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);  // Bind the ID as an integer parameter
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    $_SESSION['message'] = 'Error: Product not found.';
    $_SESSION['alert_type'] = 'danger'; // red bar for errors
    header("Location: foodMenu.php");
    exit();
}

// Toggle the 'is_disabled' status
$new_status = $product['is_disabled'] == 1 ? 0 : 1;

// Update the product's 'is_disabled' status
$sql = "UPDATE products SET is_disabled = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $new_status, $id); // Bind new status and product ID as integers
$stmt->execute();

// Set session for notification
$status = $new_status == 1 ? "disabled" : "enabled";
$_SESSION['message'] = "Product {$status} successfully!";
$_SESSION['alert_type'] = $new_status == 1 ? 'danger' : 'success'; // red for disabled, green for enabled

// Redirect to the admin page
header("Location: foodMenu.php");
exit();
?>
