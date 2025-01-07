<?php 
session_start();
include_once "config.php";

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_SESSION['unique_id'])){
    $outgoing_id = $_SESSION['unique_id'];
    $incoming_id = isset($_POST['incoming_id']) ? mysqli_real_escape_string($conn, $_POST['incoming_id']) : null;

    if ($incoming_id === null) {
        echo json_encode(["error" => "Missing incoming_id"]);
        exit;
    }

    $output = "";

    $sql = "SELECT m.*, u.img, u.first_name, u.last_name 
            FROM messages m
            LEFT JOIN tbl_user u ON u.unique_id = m.outgoing_msg_id
            WHERE (m.outgoing_msg_id = ? AND m.incoming_msg_id = ?)
            OR (m.outgoing_msg_id = ? AND m.incoming_msg_id = ?) 
            ORDER BY m.msg_id";
    
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("iiii", $outgoing_id, $incoming_id, $incoming_id, $outgoing_id);
    
    if (!$stmt->execute()) {
        echo json_encode(["error" => "Failed to execute query: " . $stmt->error]);
        exit;
    }

    $result = $stmt->get_result();

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $img = $row['img'] ? htmlspecialchars($row['img']) : 'default.png';
            if($row['outgoing_msg_id'] == $outgoing_id){
                $output .= '<div class="chat outgoing">
                            <div class="details">
                                <p>'.htmlspecialchars($row['msg']).'</p>
                            </div>
                            </div>';
            }else{
                $output .= '<div class="chat incoming">
                            <img src="php/images/'.$img.'" alt="">
                            <div class="details">
                                <p>'.htmlspecialchars($row['msg']).'</p>
                            </div>
                            </div>';
            }
        }
    }else{
        $output .= '<div class="text">No messages are available. Once you send message they will appear here.</div>';
    }
    echo $output;
    $stmt->close();
}else{
    echo json_encode(["error" => "Not logged in"]);
}
?>

