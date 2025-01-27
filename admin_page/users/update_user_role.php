<?php
include '../../conn/conn.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $newRole = $_POST['user_role'] ?? '';

    if ($username && $newRole) {
        // Update user role in the database
        $sql = "UPDATE tbl_user SET user_role = ? WHERE username = ? AND user_role != 'admin'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $newRole, $username);
        $success = $stmt->execute();
        $stmt->close();

        // Return JSON response
        echo json_encode(['success' => $success]);
        exit;
    }
}

// Return error response if invalid request
echo json_encode(['success' => false]);
