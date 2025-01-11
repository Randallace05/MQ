<?php
include("../conn/conn.php");

// Retrieve user ID from session
$tbl_user_id = $_SESSION['tbl_user_id'] ?? null;

if ($conn && $tbl_user_id) {
    $stmt = $conn->prepare("SELECT message FROM notifications WHERE tbl_user_id = ? AND is_read = 0");
    $stmt->bind_param("i", $tbl_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $notifications = [];
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }

    $stmt->close();
    echo json_encode($notifications);
} else {
    echo json_encode([]);
}
?>
