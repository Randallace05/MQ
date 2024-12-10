<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "
    <script>
        alert('You must log in to perform this action.');
        window.location.href = '../index.php'; // Redirect to login page
    </script>
    ";
    exit;
}

// Include database connection
include '../conn/conn.php';

$tbl_user_id = intval($_SESSION['tbl_user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'remove' && isset($_POST['wish_id'])) {
        // Remove item from wishlist
        $wish_id = intval($_POST['wish_id']);
        $sql = "DELETE FROM wishlist WHERE product_id = ? AND tbl_user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $wish_id, $tbl_user_id);
        if ($stmt->execute()) {
            echo "
            <script>
                alert('Item removed from wishlist.');
                window.location.href = 'wishlist.php';
            </script>
            ";
        }
    }
}
?>
