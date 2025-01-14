<?php
include("../conn/conn.php");

// Check if the connection to the database is established
if ($conn) {
    // Query to fetch only the status column
    $query = "SELECT status FROM transaction_history WHERE notification_sent = 1";
    $result = $conn->query($query);

    // Prepare an array to hold the statuses
    $statuses = [];

    if ($result->num_rows > 0) {
        // Fetch statuses from the result
        while ($row = $result->fetch_assoc()) {
            $statuses[] = $row['status'];
        }
    }

    // Mark the notifications as sent (optional)
    $conn->query("UPDATE transaction_history SET notification_sent = 1 WHERE notification_sent = 0");

    // Return the statuses as JSON
    echo json_encode($statuses);
} else {
    // Return an empty array if there is a connection issue
    echo json_encode([]);
}

$conn->close();
?>
<script>
    
</script>