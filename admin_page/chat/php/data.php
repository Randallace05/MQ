<?php
// Initialize $output if it hasn't been defined
if (!isset($output)) {
    $output = "";
}

if (isset($users) && is_array($users)) {
    foreach ($users as $row) {
        // Prepare the second query to get the latest message between users
        $sql2 = "SELECT * FROM messages 
                 WHERE (incoming_msg_id = ? OR outgoing_msg_id = ?) 
                   AND (outgoing_msg_id = ? OR incoming_msg_id = ?) 
                 ORDER BY msg_id DESC LIMIT 1";
        
        if ($stmt2 = $conn->prepare($sql2)) {
            // Bind parameters
            $stmt2->bind_param("iiii", $row['unique_id'], $row['unique_id'], $outgoing_id, $outgoing_id);

            // Execute the statement
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $row2 = $result2->fetch_assoc();

            // Determine the latest message
            $result = $result2->num_rows > 0 ? $row2['msg'] : "No message available";

            // Shorten the message if it's too long
            $msg = strlen($result) > 28 ? substr($result, 0, 28) . '...' : $result;

            // Determine if the message was sent by the current user
            $you = isset($row2['outgoing_msg_id']) && $outgoing_id == $row2['outgoing_msg_id'] ? "You: " : "";

            // Check if the user is offline
            $offline = $row['status'] == "Offline now" ? "offline" : "";

            // Hide the current user's profile in the list of users
            $hid_me = $outgoing_id == $row['unique_id'] ? "hide" : "";

            // Escape output to prevent XSS attacks
            $unique_id = htmlspecialchars($row['unique_id']);
            $fname = htmlspecialchars($row['fname']);
            $lname = htmlspecialchars($row['lname']);
            $img = htmlspecialchars($row['img']);
            $you = htmlspecialchars($you);
            $msg = htmlspecialchars($msg);
            $offline = htmlspecialchars($offline);

            // Generate HTML output for each user
            $output .= '<a href="chat.php?user_id='. $unique_id .'">
                            <div class="content">
                                <img src="../../php/images/'. $img .'" alt="">
                                <div class="details">
                                    <span>'. $fname . " " . $lname .'</span>
                                    <p>'. $you . $msg .'</p>
                                </div>
                            </div>
                            <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
                        </a>';
        } else {
            $output .= "Error in preparing the query!";
        }
    }
} else {
    $output .= "No users are available to chat";
}
?>
