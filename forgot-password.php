<?php
session_start();
include('./conn/conn.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - MQ Kitchen</title>
    <link rel="icon" type="image/x-icon" href="uploads/sili.ico" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        /* Add your custom styles here */
        body {
            background: rgb(255,153,153);
            background: radial-gradient(circle, rgb(210, 70, 70) 0%, rgba(210, 70, 70) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .forgot-password-form {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            max-width: 400px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="forgot-password-form">
        <h2 class="text-center mb-4">Forgot Password</h2>
        <form id="forgotPasswordForm" action="endpoint/send-otp.php" method="POST">
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Send OTP</button>
        </form>
        <p class="mt-3 text-center">
            <a href="index.php">Back to Login</a>
        </p>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

