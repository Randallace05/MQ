<?php
include("../conn/conn.php");
session_start();

header('Content-Type: application/json');

// Check if tbl_user_id and notification_id are available
if (isset($_SESSION['tbl_user_id'], $_POST['notification_id'])) {
    $tbl_user_id = $_SESSION['tbl_user_id'];
    $notification_id = $_POST['notification_id']; // Get the notification ID

    // Check if the connection is successful
    if ($conn) {
        // Update the notification_sent column for the specific notification ID
        $query = "UPDATE transaction_history SET notification_sent = 1 WHERE id = ? AND tbl_user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $notification_id, $tbl_user_id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["error" => $stmt->error]);
        }

    } else {
        // Connection failed
        echo json_encode(["error" => "Connection failed"]);
    }

} else {
    // Missing parameters
    echo json_encode(["error" => "Invalid request"]);
}

$conn->close();
?>
