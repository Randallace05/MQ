<?php
while ($row = mysqli_fetch_assoc($query)) {
    $sql2 = "SELECT * FROM messages WHERE 
            (incoming_msg_id = {$row['unique_id']} OR outgoing_msg_id = {$row['unique_id']}) 
            AND (outgoing_msg_id = {$outgoing_id} OR incoming_msg_id = {$outgoing_id}) 
            ORDER BY msg_id DESC LIMIT 1";
    $query2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_assoc($query2);
    $result = (mysqli_num_rows($query2) > 0) ? $row2['msg'] : "No message available";
    $msg = (strlen($result) > 28) ? substr($result, 0, 28) . '...' : $result;

    $you = isset($row2['outgoing_msg_id']) && $outgoing_id == $row2['outgoing_msg_id'] ? "You: " : "";

    $offline = $row['status'] == "Offline now" ? "offline" : "";

    $output .= '<div class="chat-item" data-user-id="' . $row['unique_id'] . '">
                    <div class="content">
                        <img src="php/images/' . htmlspecialchars($row['img']) . '" alt="">
                        <div class="details">
                            <span>' . htmlspecialchars($row['first_name'] . " " . $row['last_name']) . '</span>
                            <p>' . htmlspecialchars($you . $msg) . '</p>
                        </div>
                    </div>
                    <div class="status-dot ' . $offline . '"><i class="fas fa-circle"></i></div>
                </div>';
}
?>
