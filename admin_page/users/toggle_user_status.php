<?php
include '../../conn/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $newStatus = $_POST['status'];
    $reason = $_POST['reason'] ?? '';
    $expiryDate = $_POST['expiryDate'] ?? null;

    if ($newStatus == 0) {
        $sql = "UPDATE tbl_user SET is_active = ?, block_reason = ?, block_expiry_date = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isss', $newStatus, $reason, $expiryDate, $username);
    } else {
        $sql = "UPDATE tbl_user SET is_active = ?, block_reason = NULL, block_expiry_date = NULL WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('is', $newStatus, $username);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}

$conn->close();
?>