<?php
// Include the database connection
include("../../conn/conn.php");

// Decode the JSON payload
$data = json_decode(file_get_contents("php://input"), true);

$order_id = $data['order_id'];
$status = $data['status'];

// Update the order status in the database and reset notification_sent
$sql = "UPDATE transaction_history SET status = ?, notification_sent = 1 WHERE order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $order_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}

$stmt->close();
$conn->close();
?>
