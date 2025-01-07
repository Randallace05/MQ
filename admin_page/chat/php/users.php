<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['unique_id'])) {
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

$outgoing_id = $_SESSION['unique_id'];

$sql = "SELECT * FROM tbl_user WHERE unique_id != ? ORDER BY tbl_user_id DESC";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
    exit;
}

$stmt->bind_param("i", $outgoing_id);

if (!$stmt->execute()) {
    echo json_encode(["error" => "Failed to execute query: " . $stmt->error]);
    exit;
}

$result = $stmt->get_result();

$output = "";

if ($result->num_rows == 0) {
    $output .= "No users are available to chat";
} else {
    while ($row = $result->fetch_assoc()) {
        $sql2 = "SELECT * FROM messages WHERE (incoming_msg_id = ? OR outgoing_msg_id = ?) 
                AND (outgoing_msg_id = ? OR incoming_msg_id = ?) ORDER BY msg_id DESC LIMIT 1";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("iiii", $row['unique_id'], $row['unique_id'], $outgoing_id, $outgoing_id);
        $stmt2->execute();
        $query2 = $stmt2->get_result();
        $row2 = $query2->fetch_assoc();

        $you = "";
        if ($query2->num_rows > 0) {
            $result = $row2['msg'];
            $you = ($outgoing_id == $row2['outgoing_msg_id']) ? "You: " : "";
        } else {
            $result = "No message available";
        }

        $offline = ($row['status'] == "Offline now") ? "offline" : "";
        $hid_me = ($outgoing_id == $row['unique_id']) ? "hide" : "";

        $output .= '<a href="chat.php?user_id='. $row['unique_id'] .'">
                    <div class="content">
                    <img src="../uploads/'. htmlspecialchars($row['img']) .'" alt="">
                    <div class="details">
                        <span>'. htmlspecialchars($row['first_name']). " " . htmlspecialchars($row['last_name']) .'</span>
                        <p>'. $you . htmlspecialchars(substr($result, 0, 28)) . (strlen($result) > 28 ? '...' : '') .'</p>
                    </div>
                    </div>
                    <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
                </a>';
    }
}

echo $output;
$stmt->close();
?>

