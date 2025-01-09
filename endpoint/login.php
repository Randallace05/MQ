<?php
session_start();
include_once "../admin_page/chat/php/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo json_encode(["error" => "Please fill in both username and password."]);
        exit;
    }
    
    $query = "SELECT unique_id, tbl_user_id, password, username, user_role FROM tbl_user WHERE username = ?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        echo json_encode(["error" => "An error occurred. Please try again later."]);
        exit;
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $status = "Active now";
            $sql2 = "UPDATE tbl_user SET status = ? WHERE unique_id = ?";
            $stmt2 = $conn->prepare($sql2);
            if ($stmt2 === false) {
                echo json_encode(["error" => "An error occurred. Please try again later."]);
                exit;
            }
            $stmt2->bind_param("si", $status, $user['unique_id']);
            $stmt2->execute();
            $stmt2->close();

            // Regenerate session ID
            session_regenerate_id(true);

            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['user_role'];
            $_SESSION['unique_id'] = $user['unique_id'];

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
            echo json_encode(["error" => "Incorrect password."]);
        }
    } else {
        echo json_encode(["error" => "User not found."]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Invalid request method."]);
}

$conn->close();
?>

