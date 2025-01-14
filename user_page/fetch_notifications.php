<?php
include("../conn/conn.php");
session_start(); // Start the session to access session variables

header('Content-Type: application/json'); // Ensure correct content type

// Check if tbl_user_id is available in the session
if (isset($_SESSION['tbl_user_id'])) {
    $tbl_user_id = $_SESSION['tbl_user_id']; // Get the tbl_user_id from the session

    // Check if the connection is successful
    if ($conn) {
        // Query to fetch the statuses for the specific tbl_user_id where notification_sent = 0
        $query = "SELECT id, status
                  FROM transaction_history
                  WHERE notification_sent = 0 AND tbl_user_id = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $tbl_user_id); // Bind the tbl_user_id parameter as integer

        if (!$stmt->execute()) {
            echo json_encode(["error" => $stmt->error]);
            exit;
        }

        // Get the result of the query
        $result = $stmt->get_result();

        // Prepare an array to hold the statuses
        $statuses = [];

        if ($result->num_rows > 0) {
            // Fetch statuses from the result
            while ($row = $result->fetch_assoc()) {
                $statuses[] = [
                    "id" => $row['id'], // Add the transaction ID for further actions
                    "status" => $row['status']
                ];
            }
        }

        // Return the statuses as JSON without updating the notification_sent column
        echo json_encode($statuses);

    } else {
        // Connection failed
        echo json_encode(["error" => "Connection failed"]);
    }

} else {
    // Error message if tbl_user_id is not set in the session
    echo json_encode(["error" => "User is not logged in or session expired"]);
}

$conn->close();
?>
