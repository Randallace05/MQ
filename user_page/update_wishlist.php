<?php
session_start();
include '../conn/conn.php';

// Verify the request is POST and valid JSON
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['product_id']) && isset($data['action'])) {
    $userId = $_SESSION['tbl_user_id']; // Assuming user is logged in
    $productId = intval($data['product_id']);
    $action = $data['action'];

    if ($action === 'add') {
        // Add product to wishlist
        $stmt = $conn->prepare("INSERT IGNORE INTO wishlist (tbl_user_id, product_id) VALUES (?, ?)");
    } elseif ($action === 'remove') {
        // Remove product from wishlist
        $stmt = $conn->prepare("DELETE FROM wishlist WHERE tbl_user_id = ? AND product_id = ?");
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
        exit;
    }

    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();
    $stmt->close();

    // Fetch updated wishlist count
    $countQuery = "SELECT COUNT(*) AS count FROM wishlist WHERE tbl_user_id = ?";
    $stmtCount = $conn->prepare($countQuery);
    $stmtCount->bind_param("i", $userId);
    $stmtCount->execute();
    $result = $stmtCount->get_result();
    $wishlistCount = $result->fetch_assoc()['count'];
    $stmtCount->close();

    echo json_encode(['status' => 'success', 'wishlist_count' => $wishlistCount]);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
exit;
?>
