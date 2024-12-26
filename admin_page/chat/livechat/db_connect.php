<?php
$host = "localhost"; // Change this if your database server is not localhost
$db_name = "login_email_verification"; // Update with your database name
$username = "root"; // Update with your database username
$password = ""; // Update with your database password

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>
