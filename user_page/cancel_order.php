<?php
include("../conn/conn.php");
session_start();

if (isset($_SESSION['tbl_user_id']) && isset($_POST['transaction_id'])) {
    $tbl_user_id = $_SESSION['tbl_user_id'];
    $transaction_id = $_POST['transaction_id'];

    // Debugging line: log the received transaction ID
    error_log("Received transaction ID: $transaction_id");

    if ($conn) {
        // Update the status to "Cancelled" for the transaction
        $updateQuery = "UPDATE transaction_history SET status = 'Cancelled', notification_sent = 1 WHERE id = ? AND tbl_user_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ii", $transaction_id, $tbl_user_id);

        if ($stmt->execute()) {
            // Debugging line: log success message
            error_log("Successfully updated status for transaction ID: $transaction_id");
            echo json_encode(['success' => true]);
        } else {
            // Debugging line: log error message
            error_log("Failed to update status for transaction ID: $transaction_id");
            echo json_encode(['success' => false, 'error' => 'Failed to cancel order']);
        }
    } else {
        error_log("Database connection failed.");
        echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    }
} else {
    // Debugging line: log invalid request
    error_log("Invalid request: User not logged in or transaction ID missing");
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}

$conn->close();
?>
