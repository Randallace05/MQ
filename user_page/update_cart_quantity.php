<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    include '../conn/conn.php';

    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
        exit;
    }

    $tbl_user_id = intval($_SESSION['tbl_user_id']);
    $cart_id = intval($_POST['cart_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 0);

    if ($cart_id <= 0 || $quantity <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid cart_id or quantity']);
        exit;
    }

    // Validate cart item ownership
    $check_query = $conn->prepare("SELECT * FROM `cart` WHERE cart_id = ? AND tbl_user_id = ?");
    $check_query->bind_param("ii", $cart_id, $tbl_user_id);
    $check_query->execute();
    $result = $check_query->get_result();

    if ($result->num_rows > 0) {
        $update_quantity_query = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE cart_id = ? AND tbl_user_id = ?");
        $update_quantity_query->bind_param("iii", $quantity, $cart_id, $tbl_user_id);
        if ($update_quantity_query->execute()) {
            echo json_encode(['success' => true, 'message' => 'Quantity updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database update failed']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Cart item not found']);
    }
}
?>
