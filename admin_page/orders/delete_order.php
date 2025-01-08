<?php
// Include the database connection
include("../../conn/conn.php");

if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);

    // Delete the order
    $sql = "DELETE FROM orders WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        echo "<script>alert('Order has been canceled successfully.');</script>";
        echo "<script>window.location.href='orders.php';</script>";
    } else {
        echo "<script>alert('Failed to cancel the order.');</script>";
        echo "<script>window.location.href='orders.php';</script>";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
