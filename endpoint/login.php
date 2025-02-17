<?php
session_start();
include_once "../admin_page/chat/php/config.php";

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo json_encode(["error" => "Please fill in both username and password."]);
        exit;
    }

    $query = "SELECT tbl_user_id, unique_id, password, username, user_role, is_active, block_expiry_date, block_reason FROM tbl_user WHERE username = ?";
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

        // Check if the user is blocked
        if ($user['is_active'] == 0) {
            $currentDate = new DateTime();
            $blockExpiryDate = new DateTime($user['block_expiry_date']);

            if ($blockExpiryDate <= $currentDate) {
                // Unblock the user if the block has expired
                $sqlUnblock = "UPDATE tbl_user SET is_active = 1, block_expiry_date = NULL, block_reason = NULL WHERE tbl_user_id = ?";
                $stmtUnblock = $conn->prepare($sqlUnblock);
                $stmtUnblock->bind_param("i", $user['tbl_user_id']);
                $stmtUnblock->execute();
                $stmtUnblock->close();
            } else {
                // Inform the user that the account is still blocked
                $blockReason = $user['block_reason'] ? " Reason: " . $user['block_reason'] : "";
                echo json_encode(["error" => "Your account has been blocked by the admin until " . $blockExpiryDate->format('Y-m-d H:i:s') . "." . $blockReason]);
                exit;
            }
        }

        // Verify password
        if (password_verify($password, $user['password'])) {
            $status = "Active now";
            $sql2 = "UPDATE tbl_user SET status = ? WHERE tbl_user_id = ?";
            $stmt2 = $conn->prepare($sql2);
            if ($stmt2 === false) {
                echo json_encode(["error" => "An error occurred. Please try again later."]);
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

            echo json_encode(["success" => true, "role" => $user['user_role']]);
        } else {
            echo json_encode(["error" => "Incorrect password."]);
        }
    } else {
        echo json_encode(["error" => "Username doesn't exist."]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Invalid request method."]);
}

$conn->close();
?>