<?php 
    session_start();
    include('./conn/conn.php'); 

    if (isset($_SESSION['user_verification_id'])) {
        $userVerificationID = $_SESSION['user_verification_id'];
    } else {
        // Redirect to the registration page if no session is found
        header("Location: ./index.php");
        exit();
    }

    // Handle verification form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify'])) {
        $enteredCode = $_POST['verification_code'];

        // Fetch the stored verification code for the user
        $stmt = $conn->prepare("SELECT `verification_code` FROM `tbl_user` WHERE `tbl_user_id` = ?");
        $stmt->bind_param("i", $userVerificationID);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && $user['verification_code'] == $enteredCode) {
            // If the code matches, update the user's status to verified
            $updateStmt = $conn->prepare("UPDATE `tbl_user` SET `verified` = 1 WHERE `tbl_user_id` = ?");
            $updateStmt->bind_param("i", $userVerificationID);
            $updateStmt->execute();

            // Redirect to index.php
            echo "
            <script>
                alert('Verification successful. Redirecting to the home page.');
                window.location.href = './index.php';
            </script>
            ";
            exit();
        } else {
            // Display an error message if the code doesn't match
            $error = "Invalid verification code. Please try again.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MQ Verification</title>
    <link rel="icon" type="image/x-icon" href="uploads/sili.ico" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgb(255,153,153);
            background: radial-gradient(circle, rgb(210, 70, 70) 0%, rgba(210, 70, 70) 100%);
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100vh;
            background-image: url('uploads/try.png');
        }

        .verification-form {
            backdrop-filter: blur(100px);
            color: rgb(255, 255, 255);
            padding: 40px;
            width: 500px;
            border: 2px solid;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="main">
        <!-- Email Verification Area -->
        <div class="verification-container">
            <div class="verification-form" id="loginForm">
                <h2 class="text-center">Email Verification</h2>
                <p class="text-center">Please check your email for the verification code.</p>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger text-center" role="alert">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="">
                    <input type="hidden" name="user_verification_id" value="<?= htmlspecialchars($userVerificationID) ?>">
                    <div class="form-group">
                        <label for="verificationCode">Enter Verification Code:</label>
                        <input type="number" class="form-control text-center" id="verificationCode" name="verification_code" required>
                    </div>
                    <button type="submit" class="btn btn-secondary login-btn form-control mt-4" name="verify">Verify</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
