<?php
// Connection to the database using mysqli
$servername = "localhost";
$username = "root";
$password = "";
$db = "login_email_verification";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>