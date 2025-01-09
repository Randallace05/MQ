<?php
    session_start();
    include_once "config.php";

    // Validate session
    if (!isset($_SESSION['unique_id'])) {
        echo "Unauthorized access. Please log in.";
        exit;
    }

    $outgoing_id = $_SESSION['unique_id'];

    // Query users excluding the current logged-in user
    $sql = "SELECT * FROM tbl_user WHERE tbl_user_id != {$outgoing_id} ORDER BY tbl_user_id DESC";
    $query = mysqli_query($conn, $sql);
    $output = "";

    if (mysqli_num_rows($query) == 0) {
        $output .= "No users are available to chat";
    } elseif (mysqli_num_rows($query) > 0) {
        include_once "data.php";
    }

    echo $output;
?>
