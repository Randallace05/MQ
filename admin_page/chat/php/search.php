<?php
    session_start();
    include_once "config.php";

    $outgoing_id = $_SESSION['unique_id'];
    $searchTerm = mysqli_real_escape_string($conn, $_POST['searchTerm']);
    $current_user_role = $_SESSION['user_role'];

    if ($current_user_role === 'distributor') {
        $sql = "SELECT * FROM tbl_user WHERE user_role = 'admin' AND (first_name LIKE '%{$searchTerm}%' OR last_name LIKE '%{$searchTerm}%')";
    } elseif ($current_user_role === 'admin') {
        $sql = "SELECT * FROM tbl_user WHERE user_role = 'distributor' AND (first_name LIKE '%{$searchTerm}%' OR last_name LIKE '%{$searchTerm}%')";
    } else {
        $sql = "SELECT * FROM tbl_user WHERE 1 = 0"; // No results for other roles, including customers
    }

    $output = "";
    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0){
        include_once "data.php";
    }else{
        $output .= 'No user found related to your search term';
    }
    echo $output;
?>

