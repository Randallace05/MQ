<?php
session_start();
if (isset($_SESSION['unique_id'])) {
    include_once "config.php";
    $outgoing_id = $_SESSION['unique_id'];
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $output = "";

    $sql = "SELECT * FROM messages
            LEFT JOIN tbl_user ON tbl_user.unique_id = messages.outgoing_msg_id
            WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id})
               OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id})
            ORDER BY msg_id";

    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            if ((int)$row['outgoing_msg_id'] === (int)$outgoing_id) { // Sent by the logged-in user
                $output .= '
                <div class="chat outgoing">
                    <div class="details">
                        <p>' . htmlspecialchars($row['msg']) . '</p>
                    </div>
                </div>';
            } else { // Received by the logged-in user
                $output .= '
                <div class="chat incoming">
                    <img src="php/images/' . htmlspecialchars($row['img'] ?? 'default.png') . '" alt="User Image">
                    <div class="details">
                        <p>' . htmlspecialchars($row['msg']) . '</p>
                    </div>
                </div>';
            }
        }
    } else {
        $output .= '<div class="text">No messages are available. Once you send a message, they will appear here.</div>';
    }
    echo $output;
} else {
    header("location: ../login.php");
}
?>
