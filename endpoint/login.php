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

    $query = "SELECT tbl_user_id, unique_id, password, username, user_role, is_active FROM tbl_user WHERE username = ?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        $_SESSION['login_error'] = "An error occurred. Please try again later.";
        header("Location: ../index.php");
        exit;
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['is_active'] == 0) {
            $_SESSION['login_error'] = "Your account has been disabled. Please contact the administrator.";
            header("Location: ../index.php");
            exit;
        }
        if (password_verify($password, $user['password'])) {
            $status = "Active now";
            $sql2 = "UPDATE tbl_user SET status = ? WHERE tbl_user_id = ?";
            $stmt2 = $conn->prepare($sql2);
            if ($stmt2 === false) {
                $_SESSION['login_error'] = "An error occurred. Please try again later.";
                header("Location: ../index.php");
                exit;
            }
            $stmt2->bind_param("si", $status, $user['tbl_user_id']);
            $stmt2->execute();
            $stmt2->close();

            // Regenerate session ID
            session_regenerate_id(true);

            $_SESSION['unique_id'] = $user['unique_id'];
            $_SESSION['tbl_user_id'] = $user['tbl_user_id'];
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['user_role'];


            // Redirect based on user role
            switch ($user['user_role']) {
                case 'admin':
                    header("Location: ../admin_page/dashboard/index.php");
                    break;
                case 'customer':
                    header("Location: ../user_page/shop.php");
                    break;
                case 'distributor':
                    header("Location: ../user_page/shop.php");
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

    $stmt->close();
} else {
    header("Location: ../index.php");
}

$conn->close();
?>

