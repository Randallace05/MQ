<?php
session_start();
if (isset($_SESSION['unique_id'])) {
    include_once "../../../conn/conn.php"; // Include your connection file
    $outgoing_id = $_SESSION['unique_id'];
    $incoming_id = $_POST['incoming_id'];
    $output = "";

    // Corrected SQL query
    $sql = "SELECT * FROM messages 
            LEFT JOIN tbl_user ON tbl_user.unique_id = messages.outgoing_msg_id
            WHERE (outgoing_msg_id = ? AND incoming_msg_id = ?)
            OR (outgoing_msg_id = ? AND incoming_msg_id = ?) 
            ORDER BY msg_id";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("iiii", $outgoing_id, $incoming_id, $incoming_id, $outgoing_id);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch each message and output accordingly
            while ($row = $result->fetch_assoc()) {
                if ($row['outgoing_msg_id'] === $outgoing_id) {
                    $output .= '<div class="chat outgoing">
                                <div class="details">
                                    <p>' . htmlspecialchars($row['msg']) . '</p>
                                </div>
                                </div>';
                } else {
                    $output .= '<div class="chat incoming">
                                <img src="php/images/' . htmlspecialchars($row['img']) . '" alt="">
                                <div class="details">
                                    <p>' . htmlspecialchars($row['msg']) . '</p>
                                </div>
                                </div>';
                }
            }
        } else {
            $output .= '<div class="text">No messages are available. Once you send a message, they will appear here.</div>';
        }

        // Close the statement
        $stmt->close();
    } else {
        $output .= '<div class="text">Error in preparing the query!</div>';
    }

    echo $output;
} else {
    header("location: ../login.php");
    exit();
}
?>
