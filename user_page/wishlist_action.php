<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../index.php');
    exit;
}

// Include database connection
include '../conn/conn.php';

// Check if an action is submitted
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add_to_cart') {
        $product_id = intval($_POST['product_id']);
        $tbl_user_id = intval($_SESSION['tbl_user_id']);

        // Start a transaction to ensure atomicity
        $conn->begin_transaction();

        try {
            // Check stock availability
            $stockCheckSql = "SELECT stock FROM products WHERE id = ?";
            $stockStmt = $conn->prepare($stockCheckSql);
            $stockStmt->bind_param("i", $product_id);
            $stockStmt->execute();
            $stockResult = $stockStmt->get_result();

            if ($stockResult->num_rows > 0) {
                $product = $stockResult->fetch_assoc();
                if ($product['stock'] > 0) {
                    // Decrease stock by 1
                    $updateStockSql = "UPDATE products SET stock = stock - 1 WHERE id = ?";
                    $updateStockStmt = $conn->prepare($updateStockSql);
                    $updateStockStmt->bind_param("i", $product_id);
                    $updateStockStmt->execute();

                    // Remove item from wishlist
                    $deleteWishlistSql = "DELETE FROM wishlist WHERE tbl_user_id = ? AND product_id = ?";
                    $deleteWishlistStmt = $conn->prepare($deleteWishlistSql);
                    $deleteWishlistStmt->bind_param("ii", $tbl_user_id, $product_id);
                    $deleteWishlistStmt->execute();

                    // Add item to cart
                    $addToCartSql = "INSERT INTO cart (tbl_user_id, product_id, quantity) VALUES (?, ?, 1)";
                    $addToCartStmt = $conn->prepare($addToCartSql);
                    $addToCartStmt->bind_param("ii", $tbl_user_id, $product_id);
                    $addToCartStmt->execute();

                    // Commit transaction
                    $conn->commit();

                    $_SESSION['success_message'] = "Item added to cart successfully!";
                } else {
                    throw new Exception("Product is out of stock.");
                }
            } else {
                throw new Exception("Product not found.");
            }
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['error_message'] = $e->getMessage();
        }

        header('Location: wishlist.php');
        exit;
    }

    // Handle other actions (e.g., remove from wishlist)
    if ($action === 'remove') {
        $wishlist_id = intval($_POST['wishlist_id']);

        $deleteSql = "DELETE FROM wishlist WHERE wish_id = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param("i", $wishlist_id);

        if ($deleteStmt->execute()) {
            $_SESSION['success_message'] = "Item removed from wishlist.";
        } else {
            $_SESSION['error_message'] = "Failed to remove item from wishlist.";
        }

        header('Location: wishlist.php');
        exit;
    }
}
?>
