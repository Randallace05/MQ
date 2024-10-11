<?php
session_start();

// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    // If the current page is not the login or registration page, redirect to login
    if ($current_page !== 'index.php' && $current_page !== 'register.php') {
        header('Location: /MQ-2/index.php');  // Use an absolute path to redirect
        exit();
    }
}
?>

