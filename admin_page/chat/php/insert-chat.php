<?php 
session_start();
if (isset($_SESSION['unique_id'])) {
    include_once "config.php";

    // Validate and sanitize inputs
    $outgoing_id = $_SESSION['unique_id'];
    $incoming_id = isset($_POST['incoming_id']) ? mysqli_real_escape_string($conn, $_POST['incoming_id']) : null;
    $message = isset($_POST['message']) ? mysqli_real_escape_string($conn, $_POST['message']) : null;

    // Ensure all required data is provided
    if (!empty($incoming_id) && !empty($message)) {
        // Use prepared statements for better security
        $stmt = $conn->prepare("INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg) VALUES (?, ?, ?)");
        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("iis", $incoming_id, $outgoing_id, $message);

            // Execute the query and check for success
            if ($stmt->execute()) {
                echo "Message sent successfully.";
            } else {
                echo "Failed to send message. Please try again.";
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Error preparing the query.";
        }
    } else {
        echo "Message or recipient is missing.";
    }
} else {
    header("location: ../login.php");
    exit();
}
?>
