<?php
include("../conn/conn.php");
session_start();

if (isset($_SESSION['tbl_user_id'])) {
    $tbl_user_id = $_SESSION['tbl_user_id'];

    if ($conn) {
        // Update notification_sent to 1 for all current notifications
        $updateQuery = "UPDATE transaction_history SET notification_sent = 1 WHERE tbl_user_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("i", $tbl_user_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to clear notifications']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'User is not logged in or session expired']);
}

$conn->close();
?>
