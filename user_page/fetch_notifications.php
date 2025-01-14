<?php
include("../conn/conn.php");
session_start();

// Check if user is logged in
if (isset($_SESSION['tbl_user_id'])) {
    $tbl_user_id = $_SESSION['tbl_user_id'];

    if ($conn) {
        // Fetch notifications from transaction_history where notification_sent = 0 or 1
        $query = "SELECT id, status, notification_sent FROM transaction_history WHERE tbl_user_id = ? ORDER BY id DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $tbl_user_id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $transactions = [];

            // Loop through the transactions and fetch the status updates
            while ($row = $result->fetch_assoc()) {
                // Add a cancel_order field if the status is "Order Placed"
                $row['cancel_order'] = ($row['status'] === 'Order Placed') ? true : false;
                $transactions[] = $row;
            }

            // Return the transactions as JSON (acting as notifications)
            echo json_encode($transactions);
        } else {
            echo json_encode(["error" => "Failed to fetch notifications"]);
        }
    } else {
        echo json_encode(["error" => "Database connection failed"]);
    }
} else {
    echo json_encode(["error" => "User is not logged in or session expired"]);
}

$conn->close();
?>
