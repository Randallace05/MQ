<?php
    session_start();
    include_once "config.php";
    $outgoing_id = $_SESSION['unique_id'];
    $current_user_role = $_SESSION['user_role'];

    if ($current_user_role === 'distributor') {
        $sql = "SELECT * FROM tbl_user WHERE user_role = 'admin' ORDER BY tbl_user_id DESC";
    } elseif ($current_user_role === 'admin') {
        $sql = "SELECT * FROM tbl_user WHERE user_role = 'distributor' ORDER BY tbl_user_id DESC";
    } else {
        $sql = "SELECT * FROM tbl_user WHERE 1 = 0"; // No results for other roles, including customers
    }

    $query = mysqli_query($conn, $sql);
    $output = "";
    if(mysqli_num_rows($query) == 0){
        $output .= "No users are available to chat";
    }elseif(mysqli_num_rows($query) > 0){
        include_once "data.php";
    }
    echo $output;
?>

