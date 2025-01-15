<?php
include("../conn/conn.php");
session_start();

header('Content-Type: application/json');

if (isset($_SESSION['tbl_user_id'])) {
    $tbl_user_id = $_SESSION['tbl_user_id'];

    if ($conn) {
        // Fetch notifications where notification_sent = 0
        $query = "SELECT id, status, cart_items FROM transaction_history WHERE tbl_user_id = ? AND notification_sent = 0 ORDER BY id DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $tbl_user_id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $notifications = [];

            while ($row = $result->fetch_assoc()) {
                $notifications[] = $row;
            }

            echo json_encode($notifications);
        } else {
            echo json_encode(["error" => "Failed to fetch notifications."]);
        }
    } else {
        echo json_encode(["error" => "Database connection failed."]);
    }
} else {
    echo json_encode(["error" => "User is not logged in or session expired."]);
}

$conn->close();
?>
