<?php
// change_password.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("../includes/topbar1.php");
include '../conn/conn.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "<script>
        alert('You must log in to change your password.');
        window.location.href = '../index.php';
    </script>";
    exit;
}

$tbl_user_id = intval($_SESSION['tbl_user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($new_password !== $confirm_password) {
        echo "<script>
            alert('New password and confirm password do not match.');
        </script>";
    } else {
        $password_query = $conn->prepare("SELECT password FROM tbl_user WHERE tbl_user_id = ?");
        $password_query->bind_param("i", $tbl_user_id);
        $password_query->execute();
        $password_result = $password_query->get_result();
        $password_data = $password_result->fetch_assoc();

        if ($password_data && password_verify($current_password, $password_data['password'])) {
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_password_query = $conn->prepare("UPDATE tbl_user SET password = ? WHERE tbl_user_id = ?");
            $update_password_query->bind_param("si", $hashed_new_password, $tbl_user_id);

            if ($update_password_query->execute()) {
                echo "<script>
                    alert('Password changed successfully.');
                    window.location.href = 'profile_page.php';
                </script>";
            } else {
                echo "<script>
                    alert('Failed to change password.');
                </script>";
            }
        } else {
            echo "<script>
                alert('Current password is incorrect.');
            </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #565e64;
            border-color: #4e555b;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Change Password</h1>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input
                type="password"
                class="form-control"
                id="new_password"
                name="new_password"
                required
                pattern="^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$"
                title="Password must be at least 8 characters long, contain at least one special character (!@#$%^&*), and at least one number."
            >
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input
                type="password"
                class="form-control"
                id="confirm_password"
                name="confirm_password"
                required
                pattern="^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$"
                title="Password must be at least 8 characters long, contain at least one special character (!@#$%^&*), and at least one number."
            >
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Change Password</button>
            <a href="profile_page.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
