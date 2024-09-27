<?php
include "../../../conn/conn.php";

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in
if (!isset($_SESSION['unique_id'])) {
    echo "Session not set. Please log in.";
    exit();
}

$outgoing_id = $_SESSION['unique_id'];

try {
    // Corrected SQL query to exclude the current user
    $sql = "SELECT * FROM tbl_user WHERE unique_id != :outgoing_id ORDER BY tbl_user_id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':outgoing_id', $outgoing_id, PDO::PARAM_INT);
    $stmt->execute();

    $output = "";

    if ($stmt->rowCount() == 0) {
        $output .= "No users are available to chat";
    } else {
        // Fetch all users
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Pass the users array to data.php for processing
        include_once "data.php";
    }

    echo $output;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
