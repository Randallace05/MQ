<?php
include 'db_connection.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the batchId and status from the POST request
    $batchId = $_POST['batchId'];
    $status = $_POST['status'];

    // Check if both parameters are set
    if (isset($batchId) && isset($status)) {
        // Update the batch status in the database
        $query = "UPDATE product_batches SET status = ? WHERE batch_number = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("ii", $status, $batchId);
            if ($stmt->execute()) {
                // Send success response
                echo json_encode(['success' => true, 'message' => 'Batch status updated successfully.']);
            } else {
                // Send error response
                echo json_encode(['success' => false, 'message' => 'Failed to update batch status.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to prepare the SQL query.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>