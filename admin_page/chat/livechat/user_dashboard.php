<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: index.php");
    exit;
}
echo "<h1>Welcome, User " . $_SESSION['username'] . "!</h1>";
?>
