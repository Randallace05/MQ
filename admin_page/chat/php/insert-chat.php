<?php
session_start();
include_once "config.php";

if(isset($_SESSION['unique_id'])){
    $outgoing_id = $_SESSION['unique_id'];
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    if(!empty($message)){
        $sql = "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg) 
                VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $incoming_id, $outgoing_id, $message);
        
        if($stmt->execute()){
            echo "Message sent successfully";
        } else {
            echo "Failed to send message";
        }
        $stmt->close();
    }
} else {
    echo "Not logged in";
}
?>

