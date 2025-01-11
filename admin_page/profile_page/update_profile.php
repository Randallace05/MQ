<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['unique_id'])) {
    echo "Session 'unique_id' is not set.";
    exit;
}

// Include database connection
include '../../conn/conn.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate user input
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $contact_number = trim($_POST['contact_number']);

    if (empty($first_name) || empty($last_name) || empty($email)) {
        echo "Please fill in all required fields.";
        exit;
    }

    // Update the user's information in the database
    $sql = "UPDATE tbl_user SET first_name = ?, last_name = ?, email = ?, contact_number = ? WHERE unique_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing the query: " . $conn->error);
    }

    $stmt->bind_param("sssss", $first_name, $last_name, $email, $contact_number, $_SESSION['unique_id']);

    if ($stmt->execute()) {
        // Redirect to the dashboard after a successful update
        header("Location: ../dashboard/index.php?status=success");
        exit;
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
