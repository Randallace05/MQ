<?php
session_start();
include_once "config.php";

$outgoing_id = $_SESSION['unique_id'];

// Prepare the SQL query using MySQLi
$sql = "SELECT * FROM tbl_user WHERE tbl_user_id != ? ORDER BY tbl_user_id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $outgoing_id); // Bind the parameter (integer)

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Initialize output to avoid undefined variable errors
$output = "";

if ($result->num_rows == 0) {
    $output .= "No users are available to chat";
} else {
    // Fetch all users and include the data.php file
    $users = $result->fetch_all(MYSQLI_ASSOC); // Fetch all users as an associative array
    include_once "data.php"; // Pass $users to data.php
}

echo $output;
?>
