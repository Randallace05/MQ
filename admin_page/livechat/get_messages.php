<?php
session_start();
include '../../conn/conn.php';

$sender_id = $_SESSION['user_id'];
$receiver_id = $_GET['receiver_id'];

try {
    $stmt = $conn->prepare("SELECT * FROM messages WHERE (sender_id = :sender_id AND receiver_id = :receiver_id) OR (sender_id = :receiver_id AND receiver_id = :sender_id) ORDER BY timestamp");
    $stmt->bindParam(':sender_id', $sender_id, PDO::PARAM_INT);
    $stmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
    $stmt->execute();

    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($messages);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null; // Close the connection
?>