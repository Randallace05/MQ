<?php
session_start();
include("C:/xampp/htdocs/MQ/conn/conn.php");
 // Include your database connection

// Validate user login credentials
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check user in the database
    $query = "SELECT * FROM tbl_user WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_role'] = $user['user_role'];

        // Redirect to dashboard or homepage
        header("Location: shop.php");
        exit;
    } else {
        // Invalid credentials
        $_SESSION['error_message'] = "Invalid username or password";
        header("Location: index.php");
        exit;
    }
}
?>
