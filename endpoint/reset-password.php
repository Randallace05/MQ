<?php
session_start();
include('../conn/conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $otp = $_POST['otp'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_SESSION['reset_email'];

    if ($new_password !== $confirm_password) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
        exit();
    }

    // Verify OTP
    $stmt = $conn->prepare("SELECT tbl_user_id FROM tbl_user WHERE email = ? AND verification_code = ?");
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $updateStmt = $conn->prepare("UPDATE tbl_user SET password = ?, verification_code = NULL WHERE email = ?");
        $updateStmt->bind_param("ss", $hashed_password, $email);
        $updateStmt->execute();

        unset($_SESSION['reset_email']);
        echo json_encode(['success' => true, 'message' => 'Password reset successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid OTP']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

