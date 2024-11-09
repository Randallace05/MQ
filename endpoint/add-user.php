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
        // $uniqueID = rand(time(), 100000000);
        $uniqueID = uniqid();

        
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $conn->beginTransaction();
    
        $stmt = $conn->prepare("SELECT `first_name`, `last_name` FROM `tbl_user` WHERE `first_name` = :first_name AND `last_name` = :last_name");
        $stmt->execute([
            'first_name' => $firstName,
            'last_name' => $lastName
        ]);
        $nameExist = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (empty($nameExist)) {
            $verificationCode = rand(1000, 9999); // 4 digits
            //$verificationCode = rand(100000, 999999); // 6 digits
    
            $insertStmt = $conn->prepare("
                INSERT INTO `tbl_user` (
                    `tbl_user_id`, 
                    `first_name`, 
                    `last_name`, 
                    `contact_number`, 
                    `email`, 
                    `username`, 
                    `password`, 
                    `verification_code`, 
                    `unique_id`,
                    `user_role` 
                ) 
                VALUES (
                    NULL, 
                    :first_name, 
                    :last_name, 
                    :contact_number, 
                    :email, 
                    :username, 
                    :password, 
                    :verification_code, 
                    :unique_id,
                    :user_role  
                )
            ");
            $insertStmt->bindParam(':first_name', $firstName, PDO::PARAM_STR);
            $insertStmt->bindParam(':last_name', $lastName, PDO::PARAM_STR);
            $insertStmt->bindParam(':contact_number', $contactNumber, PDO::PARAM_INT);
            $insertStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $insertStmt->bindParam(':username', $username, PDO::PARAM_STR);
            $insertStmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR); // Store hashed password
            $insertStmt->bindParam(':verification_code', $verificationCode, PDO::PARAM_INT);
            $insertStmt->bindParam(':unique_id', $uniqueID, PDO::PARAM_STR); // Changed to string if using uniqid
            $insertStmt->bindParam(':user_role', $user_role, PDO::PARAM_STR); 
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
    
            $userVerificationID = $conn->lastInsertId();
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
    } catch (PDOException $e) {
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}

if (isset($_POST['verify'])) {
    try {
        session_start();
        $userVerificationID = $_SESSION['user_verification_id'] ?? null; // Retrieve ID from session
        $verificationCode = $_POST['verification_code'];
    
        // Check for a valid user ID and verification code
        if ($userVerificationID) {
            $stmt = $conn->prepare("SELECT `verification_code` FROM `tbl_user` WHERE `tbl_user_id` = :user_verification_id");
            $stmt->execute([
                'user_verification_id' => $userVerificationID,
            ]);
            $codeExist = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($codeExist && $codeExist['verification_code'] == $verificationCode) {
                session_destroy();
                echo "
                <script>
                    alert('Registered Successfully.');
                    window.location.href = '../index.php';
                </script>
                ";
            } else {
                // If verification code is incorrect, delete user entry
                $deleteStmt = $conn->prepare("DELETE FROM `tbl_user` WHERE `tbl_user_id` = :user_verification_id");
                if ($deleteStmt->execute(['user_verification_id' => $userVerificationID])) {
                    echo "
                    <script>
                        alert('Incorrect Verification Code. Register Again.');
                        window.location.href = '../index.php';
                    </script>
                    ";
                } else {
                    echo "Error: Unable to delete unverified user.";
                }
            }
        } else {
            echo "Error: User verification session not found.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>

