<?php
session_start();
if (isset($_SESSION['unique_id'])) {
    include_once "config.php";
    $logout_id = mysqli_real_escape_string($conn, $_GET['logout_id'] ?? '');

    if (!empty($logout_id)) {
        $status = "Offline now";
        $sql = mysqli_query($conn, "UPDATE tbl_user SET status = '{$status}' WHERE unique_id='{$logout_id}'");

        if ($sql) {
            session_unset(); // Clear all session variables
            session_destroy(); // Destroy the session
            header("location: ../../../index.php");
            exit();
        }
    }
}

// Default redirection if no logout_id is provided
session_unset();
session_destroy();
header("location: ../../../index.php");
exit();
?>
