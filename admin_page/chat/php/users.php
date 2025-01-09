<?php
// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

// Include database configuration
include_once "config.php";

// Check if the user is logged in and `unique_id` is set
if (!isset($_SESSION['unique_id'])) {
    die("Session unique_id is not set. Please log in.");
}

// Get the logged-in user's unique ID
$outgoing_id = $_SESSION['unique_id'];

// Prepare the SQL query to fetch all users except the logged-in user
$sql = "SELECT * FROM tbl_user WHERE unique_id != ? ORDER BY tbl_user_id DESC";

// Use prepared statements for better security
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Database query failed. Please try again later.");
}
$stmt->bind_param("i", $outgoing_id);
$stmt->execute();
$result = $stmt->get_result();

$output = "";
if ($result->num_rows == 0) {
    $output .= "No users are available to chat";
} else {
    include_once "data.php";
}
echo $output;

// Close the statement and connection
$stmt->close();
$conn->close();
?>
