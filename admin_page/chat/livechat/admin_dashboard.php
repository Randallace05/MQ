<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
echo "<h1>Welcome, Admin " . $_SESSION['username'] . "!</h1>";
?>
