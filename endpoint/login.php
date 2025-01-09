<?php
session_start();
include_once "../admin_page/chat/php/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $_SESSION['login_error'] = "Please fill in both username and password.";
        header("Location: ../index.php");
        exit;
    }

    // Query the database
    $sql = mysqli_query($conn, "SELECT tbl_user_id, password, username, user_role FROM tbl_user WHERE username = '{$username}'");
    if (!$sql) {
        $_SESSION['login_error'] = "An error occurred. Please try again later.";
        header("Location: ../index.php");
        exit;
    }

    if (mysqli_num_rows($sql) > 0) {
        $user = mysqli_fetch_assoc($sql);

        if (password_verify($password, $user['password'])) {
            $status = "Active now";
            $update_sql = "UPDATE tbl_user SET status = '{$status}' WHERE tbl_user_id = {$user['tbl_user_id']}";
            if (!mysqli_query($conn, $update_sql)) {
                $_SESSION['login_error'] = "An error occurred. Please try again later.";
                header("Location: ../index.php");
                exit;
            }

            // Regenerate session ID
            session_regenerate_id(true);

            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['user_role'];
            $_SESSION['unique_id'] = $user['tbl_user_id'];

            // Redirect based on user role
            switch ($user['user_role']) {
                case 'admin':
                    header("Location: ../admin_page/dashboard/index.php");
                    break;
                case 'customer':
                    header("Location: ../user_page/shop.php");
                    break;
                case 'distributor':
                    header("Location: ../distributor_page/landing_page/index.php");
                    break;
                default:
                    $_SESSION['login_error'] = "An error occurred. Please try again later.";
                    header("Location: ../index.php");
                    break;
            }
            exit();
        } else {
            $_SESSION['login_error'] = "Incorrect password.";
            header("Location: ../index.php");
        }
    } else {
        $_SESSION['login_error'] = "User not found.";
        header("Location: ../index.php");
    }
} else {
    header("Location: ../index.php");
}

mysqli_close($conn);
?>
