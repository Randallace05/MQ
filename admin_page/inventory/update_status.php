<?php
// Include database connection
include("../../conn/conn.php");

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['order_id']) && isset($data['status'])) {
    $order_id = $conn->real_escape_string($data['order_id']);
    $status = $conn->real_escape_string($data['status']);

    // Update the status in the database
    $sql = "UPDATE transaction_history SET status='$status' WHERE order_id='$order_id'";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid data"]);
}

$conn->close();
?>
