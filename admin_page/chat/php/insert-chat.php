<?php 
session_start();
if (isset($_SESSION['unique_id'])) {
    include_once "../../../conn/conn.php";
    $outgoing_id = $_SESSION['unique_id'];
    $incoming_id = $_POST['incoming_id']; // Assuming you trust this input or sanitize it properly
    $message = $_POST['message']; // Assuming you trust this input or sanitize it properly

    if (!empty($message)) {
        try {
            // Prepare SQL statement
            $sql = "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg) VALUES (:incoming_id, :outgoing_id, :message)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':incoming_id', $incoming_id);
            $stmt->bindParam(':outgoing_id', $outgoing_id);
            $stmt->bindParam(':message', $message);
            $stmt->execute();
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage()); // Handle any errors
        }
    }
} else {
    header("location: ../login.php");
}
?>