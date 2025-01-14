<?php
include("../conn/conn.php");
session_start(); // Start the session to access session variables

header('Content-Type: application/json'); // Ensure correct content type

// Check if tbl_user_id is available in the session
if (isset($_SESSION['tbl_user_id'])) {
    $tbl_user_id = $_SESSION['tbl_user_id']; // Get the tbl_user_id from the session

    // Check if the connection is successful
    if ($conn) {
        // Update notification_sent to 1 to mark notifications as read
        $updateQuery = "UPDATE transaction_history SET notification_sent = 1 WHERE tbl_user_id = ? AND notification_sent = 0";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("i", $tbl_user_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to clear notifications']);
        }

    } else {
        echo json_encode(['success' => false, 'error' => 'Connection failed']);
    }

} else {
    // Error message if tbl_user_id is not set in the session
    echo json_encode(['success' => false, 'error' => 'User is not logged in or session expired']);
}

$conn->close();
?>
