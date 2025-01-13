<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['unique_id'])) {
    echo "Session expired. Please log in again.";
    exit;
}

$outgoing_id = $_SESSION['unique_id'];
$incoming_id = isset($_POST['incoming_id']) ? mysqli_real_escape_string($conn, $_POST['incoming_id']) : null;
$message = isset($_POST['message']) ? mysqli_real_escape_string($conn, $_POST['message']) : null;

if ($incoming_id && $message) {
    $sql = "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg) VALUES ('$incoming_id', '$outgoing_id', '$message')";
    $query = mysqli_query($conn, $sql) or die(mysqli_error($conn));

    if ($query) {
        echo "Message sent successfully.";
    } else {
        echo "Failed to send the message.";
    }
} else {
    echo "Required data missing. Message not sent.";
}
?>
