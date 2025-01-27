<?php
include('../conn/conn.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

if (isset($_POST['register'])) {
    try {
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $contactNumber = $_POST['contact_number'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user_role = $_POST['user_role']; 

        // Generate a unique user ID
        $uniqueID = uniqid();

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Set a default image
        $defaultImage = '../uploads/default.png';

        // Begin a transaction
        $conn->begin_transaction();

        $stmt = $conn->prepare("SELECT `first_name`, `last_name` FROM `tbl_user` WHERE `first_name` = ? AND `last_name` = ?");
        $stmt->bind_param("ss", $firstName, $lastName);
        $stmt->execute();
        $result = $stmt->get_result();
        $nameExist = $result->fetch_assoc();

        if (empty($nameExist)) {
            $verificationCode = rand(100000, 999999); // 4 digits

            $insertStmt = $conn->prepare("
                INSERT INTO `tbl_user` (
                    `first_name`, 
                    `last_name`, 
                    `contact_number`, 
                    `email`, 
                    `username`, 
                    `password`, 
                    `verification_code`, 
                    `unique_id`,
                    `user_role`,
                    `img`
                ) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $insertStmt->bind_param(
                "ssisssisss",
                $firstName,
                $lastName,
                $contactNumber,
                $email,
                $username,
                $hashedPassword,
                $verificationCode,
                $uniqueID,
                $user_role,
                $defaultImage
            );
            $insertStmt->execute();

            // Server settings
            $mail->isSMTP(); 
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true; 
            $mail->Username   = 'lorem.ipsum.sample.email@gmail.com';
            $mail->Password   = 'novtycchbrhfyddx'; // SMTP password
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;                                    

            // Recipients
            $mail->setFrom('MQKitchen@gmail.com', 'MQ Kitchen');
            $mail->addAddress($email);   
            $mail->addReplyTo('MQKitchen@gmail.com', 'MQ Kitchen'); 
        
            // Content
            $mail->isHTML(true);  // Enable HTML content
            $mail->Subject = 'Verification Code';
            $mail->Body    = '
                <html>
                <body>
                    <h1>Welcome to MQ Kitchen!</h1>
                    <p>Dear ' . htmlspecialchars($firstName . ' ' . $lastName) . ',</p>
                    <p>Thank you for registering with us.</p>
                    <p>Your verification code is: 
                        <strong><span style="font-size:24px; color: #d24444;">' . htmlspecialchars($verificationCode) . '</span></strong>
                    </p>
                    <p>Please enter this code in the verification page to complete your registration process.</p>
                    <p>If you did not register for this service, please disregard this email.</p>
                    <br>
                    <p>Best regards,</p>
                    <p>MQ Kitchen Team</p>
                </body>
                </html>
            ';

            // Send verification email
            $mail->send();
            
            session_start();
    
            $userVerificationID = $conn->insert_id;
            $_SESSION['user_verification_id'] = $userVerificationID;

            echo "
            <script>
                alert('Check your email for verification code.');
                window.location.href = '../verification.php';
            </script>
            ";

            $conn->commit();
        } else {
            echo "
            <script>
                alert('User Already Exists');
                window.location.href = '../index.php';
            </script>
            ";
        }
    } catch (mysqli_sql_exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}
?>
