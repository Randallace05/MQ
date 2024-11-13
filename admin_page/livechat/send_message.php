<?php
session_start();
include '../../conn/conn.php'; // Connect to your database

$sender_id = $_SESSION['user_id']; // Get the logged-in user's ID
$receiver_id = $_POST['receiver_id']; // Get the receiver's ID (admin or customer)
$message = $_POST['message']; // The message content

if(!empty($message)) {
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
?>
