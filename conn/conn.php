<?php 
// Connection to the database using PDO
$servername = "localhost";
$username = "root";
$password = "";
$db = "login_email_verification";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
    // Set PDO error mode to exception for better error handling
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
