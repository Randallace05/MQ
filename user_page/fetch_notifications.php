<?php
include("../conn/conn.php");

header('Content-Type: application/json'); // Ensure correct content type

if ($conn) {
    // Query to fetch only the status column
    $query = "SELECT status FROM transaction_history WHERE notification_sent = 1";
    $result = $conn->query($query);

    if (!$result) {
        echo json_encode(["error" => $conn->error]);
        exit;
    }

    // Prepare an array to hold the statuses
    $statuses = [];

    if ($result->num_rows > 0) {
        // Fetch statuses from the result
        while ($row = $result->fetch_assoc()) {
            $statuses[] = $row['status'];
        }
    }

    // Optional: Update notification_sent status
    $updateQuery = "UPDATE transaction_history SET notification_sent = 1 WHERE notification_sent = 0";
    if (!$conn->query($updateQuery)) {
        echo json_encode(["error" => $conn->error]);
        exit;
    }

    // Return the statuses as JSON
    echo json_encode($statuses);
} else {
    // Return an empty array if there is a connection issue
    echo json_encode(["error" => "Connection failed"]);
}

$conn->close();
?>
