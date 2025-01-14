<?php
include("../conn/conn.php");
session_start();

// Check if tbl_user_id is available in the session
if (isset($_SESSION['tbl_user_id']) && isset($_POST['transaction_id']) && isset($_POST['status'])) {
    $tbl_user_id = $_SESSION['tbl_user_id'];
    $transaction_id = $_POST['transaction_id'];
    $status = $_POST['status'];

    // Check if the connection is successful
    if ($conn) {
        // 1. Update the transaction status
        $updateQuery = "UPDATE transaction_history SET status = ?, notification_sent = 0 WHERE id = ? AND tbl_user_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sii", $status, $transaction_id, $tbl_user_id);

        if ($stmt->execute()) {
            // 2. Insert a new notification after updating the status
            $insertNotificationQuery = "INSERT INTO notifications (tbl_user_id, status, notification_sent)
                                         VALUES (?, ?, 0)";  // 0 means the notification is new and hasn't been read yet
            $insertStmt = $conn->prepare($insertNotificationQuery);
            $insertStmt->bind_param("is", $tbl_user_id, $status);

            if ($insertStmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Transaction status updated and notification created']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to insert notification']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update status']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    }

} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request or session expired']);
}

$conn->close();
?>
