<?php
    // Assume $outgoing_id is already set from session or elsewhere
    // Ensure that the query for fetching users is correct
    $sql = "SELECT * FROM tbl_user WHERE unique_id != {$outgoing_id} ORDER BY tbl_user_id DESC";
    $query = mysqli_query($conn, $sql); // Initialize query variable

    if (!$query) {
        // Check if query was successful
        die("Query failed: " . mysqli_error($conn));
    }

    while($row = mysqli_fetch_assoc($query)) {
        // Inner query to get the latest message for each user
        $sql2 = "SELECT * FROM messages WHERE (incoming_msg_id = {$row['unique_id']}
                OR outgoing_msg_id = {$row['unique_id']}) AND (outgoing_msg_id = {$outgoing_id} 
                OR incoming_msg_id = {$outgoing_id}) ORDER BY msg_id DESC LIMIT 1";
        $query2 = mysqli_query($conn, $sql2); // Get the result of second query

        if (!$query2) {
            die("Second query failed: " . mysqli_error($conn));
        }

        $row2 = mysqli_fetch_assoc($query2);
        $result = (mysqli_num_rows($query2) > 0) ? $row2['msg'] : "No message available";

        // Shorten the message if necessary
        $msg = (strlen($result) > 28) ? substr($result, 0, 28) . '...' : $result;

        // Determine if the message was from the outgoing user
        $you = isset($row2['outgoing_msg_id']) ? ($outgoing_id == $row2['outgoing_msg_id']) ? "You: " : "" : "";

        // Determine the status and visibility
        $offline = ($row['status'] == "Offline now") ? "offline" : "";
        $hid_me = ($outgoing_id == $row['unique_id']) ? "hide" : "";

        // Create the output HTML
        $output .= '<a href="chat.php?user_id=' . $row['unique_id'] . '">
                    <div class="content">
                    <img src="php/images/' . $row['img'] . '" alt="">
                    <div class="details">
                        <span>' . $row['first_name'] . " " . $row['last_name'] . '</span>
                        <p>' . $you . $msg . '</p>
                    </div>
                    </div>
                    <div class="status-dot ' . $offline . '"><i class="fas fa-circle"></i></div>
                </a>';
    }

    // Output the final HTML
    echo $output;
?>
