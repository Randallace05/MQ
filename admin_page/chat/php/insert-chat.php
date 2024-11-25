<?php 
session_start();

if (isset($_SESSION['unique_id'])) {
    include_once "../../../conn/conn.php"; // Include your connection file

    $outgoing_id = $_SESSION['unique_id'];
    $incoming_id = $_POST['incoming_id']; // Assuming you trust this input or sanitize it properly
    $message = $_POST['message']; // Assuming you trust this input or sanitize it properly

    if (!empty($message)) {
        try {
            // Prepare SQL statement using MySQLi
            $sql = "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);  // MySQLi prepared statement
            $stmt->bind_param("iis", $incoming_id, $outgoing_id, $message); // 'i' for integer, 's' for string
            $stmt->execute();
        } catch (Exception $e) {
            die("Error: " . $e->getMessage()); // Handle any errors
        }
    }
} else {
    header("location: ../login.php"); // Redirect if session is not set
}
?>
