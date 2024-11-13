<?php
session_start();
include '../../conn/conn.php'; // Connect to your database

$sender_id = $_SESSION['user_id']; // Get the logged-in user's ID
$receiver_id = $_POST['receiver_id']; // Get the receiver's ID (admin or customer)
$message = $_POST['message']; // The message content

if (!empty($message)) {
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (:sender_id, :receiver_id, :message)");
    $stmt->bindParam(':sender_id', $sender_id, PDO::PARAM_INT);
    $stmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);
    $stmt->execute();
}

$conn = null; // Close the connection
?>