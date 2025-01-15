<?php
include("../../conn/conn.php");
session_start();

// Check if POST data is received
if (isset($_POST['order_id'], $_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    if ($conn) {
        // Update the status and reset notification_sent to 0
        $updateQuery = "UPDATE transaction_history SET status = ?, notification_sent = 0 WHERE order_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("si", $status, $order_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update status']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}

$conn->close();
?>
