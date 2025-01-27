<?php
// Include the database connection
include("../../conn/conn.php");

// Check if the order_id is set
if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);

    // Fetch order details from the database
    $sql = "SELECT tbl_user.tbl_user_id, tbl_user.username, orders.*, checkout.cart_items
            FROM tbl_user
            INNER JOIN orders ON tbl_user.tbl_user_id = orders.tbl_user_id
            LEFT JOIN checkout ON orders.id = checkout.orders_id
            WHERE orders.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the order exists
    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();

        // Insert order into transaction history (use tbl_user_id instead of username)
        $insert_sql = "INSERT INTO transaction_history (order_id, tbl_user_id, order_date, total_amount, shipping_address, payment_method, cart_items, batch_codename)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param(
            "iisdssss",
            $order['id'],
            $order['tbl_user_id'], // Use tbl_user_id here
            $order['order_date'],
            $order['total_amount'],
            $order['shipping_address'],
            $order['payment_method'],
            $order['cart_items'],
            $order['batch_codename'] // Add batch_codename here
        );

        if ($insert_stmt->execute()) {
            // Delete the order from the orders table
            $delete_sql = "DELETE FROM orders WHERE id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("i", $order_id);
            $delete_stmt->execute();
        }

        // Generate and display the receipt
        echo "<!DOCTYPE html>";
        echo "<html lang='en'>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "<title>Order Receipt - Admin</title>";
        echo "<link href='https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap' rel='stylesheet'>";
        echo "<style>";
        echo "body { font-family: 'Roboto', sans-serif; margin: 0; padding: 0; background: linear-gradient(to right, #f1f2f6, #c1c8e4); display: flex; justify-content: center; align-items: center; height: 100vh; }";
        echo ".receipt { background-color: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); width: 100%; max-width: 800px; color: #333; border-top: 5px solid #3498db; }";
        echo ".receipt h1 { color: #2c3e50; font-size: 30px; margin-bottom: 30px; text-align: center; letter-spacing: 1px; }";
        echo ".receipt p { font-size: 16px; color: #555; line-height: 1.6; margin-bottom: 15px; }";
        echo ".receipt .total { font-weight: bold; font-size: 22px; color: #e74c3c; margin-top: 20px; text-align: center; }";
        echo ".receipt .highlight { color: #3498db; font-weight: bold; }";
        echo ".receipt .btn { color: #fff; background-color: #3498db; padding: 12px 30px; border-radius: 5px; text-decoration: none; font-weight: 600; display: inline-block; text-align: center; width: auto; transition: background-color 0.3s; margin-top: 20px; margin-right: 10px; }";
        echo ".receipt .btn:hover { background-color: #2980b9; }";
        echo ".receipt .section-title { font-size: 18px; font-weight: bold; color: #2c3e50; margin-top: 20px; }";
        echo ".receipt .section { background-color: #f7f8fa; padding: 15px; margin-bottom: 20px; border-radius: 8px; }";
        echo "@media (max-width: 768px) { .receipt { padding: 20px; } }";
        echo "</style>";
        echo "</head>";
        echo "<body>";

        // Receipt Content
        echo "<div class='receipt'>";
        echo "<h1>Order Receipt</h1>";

        // Customer Information Section
        echo "<div class='section'>";
        echo "<p class='section-title'>Customer Information</p>";
        echo "<p><strong>Customer:</strong> <span class='highlight'>" . htmlspecialchars($order['username']) . "</span></p>";
        echo "<p><strong>Order ID:</strong> " . htmlspecialchars($order['id']) . "</p>";
        echo "<p><strong>Order Date:</strong> " . htmlspecialchars($order['order_date']) . "</p>";
        echo "</div>";

        // Cart and Shipping Section
        echo "<div class='section'>";
        echo "<p class='section-title'>Order Details</p>";
        echo "<p><strong>Cart Items:</strong> " . htmlspecialchars($order['cart_items']) . "</p>";
        echo "<p><strong>Shipping Address:</strong> " . htmlspecialchars($order['shipping_address']) . "</p>";
        echo "<p><strong>Batch Codename:</strong> " . htmlspecialchars($order['batch_codename']) . "</p>"; // Added line for batch codename
        echo "</div>";

        // Total Amount Section
        echo "<div class='section'>";
        echo "<p class='total'><strong>Total Amount:</strong> ₱" . number_format($order['total_amount'], 2) . "</p>";
        echo "<p><strong>Payment Method:</strong> " . htmlspecialchars($order['payment_method']) . "</p>";
        echo "</div>";

        // Buttons for Print, Download, and Dashboard
        echo "<div style='text-align: center;'>";
        echo "<a href='javascript:window.print()' class='btn'>Print Receipt</a>";
        echo "<a href='download_receipt.php?order_id=" . $order['id'] . "' class='btn'>Download PDF</a>";
        echo "<a href='orders.php' class='btn'>Back to Dashboard</a>";
        echo "</div>";

        echo "</div>";

        echo "</body>";
        echo "</html>";
    } else {
        echo "<p style='text-align: center; color: #e74c3c;'>Order not found.</p>";
    }
} else {
    echo "<p style='text-align: center; color: #e74c3c;'>Invalid request.</p>";
}

// Close the database connection
$conn->close();
?>

