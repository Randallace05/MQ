<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include '../conn/conn.php';
include("../includes/topbar1.php");

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "
    <script>
        alert('You must log in to access the cart.');
        window.location.href = '../index.php'; // Redirect to the login page
    </script>";
    exit;
}

// Get the logged-in user's ID securely from the session
$tbl_user_id = intval($_SESSION['tbl_user_id']);

// Handle order cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order_id'])) {
    $cancel_order_id = intval($_POST['cancel_order_id']);

    // Begin transaction to ensure data consistency
    $conn->begin_transaction();

    try {
        // Delete related data from checkout table
        $delete_checkout_sql = "DELETE FROM checkout WHERE orders_id = ?";
        $stmt_checkout = $conn->prepare($delete_checkout_sql);
        $stmt_checkout->bind_param('i', $cancel_order_id);
        $stmt_checkout->execute();
        $stmt_checkout->close();

        // Delete the order from the orders table
        $delete_order_sql = "DELETE FROM orders WHERE id = ? AND tbl_user_id = ?";
        $stmt_order = $conn->prepare($delete_order_sql);
        $stmt_order->bind_param('ii', $cancel_order_id, $tbl_user_id);
        $stmt_order->execute();
        $stmt_order->close();

        // Commit transaction
        $conn->commit();

        echo "<script>alert('Order and related data have been cancelled successfully.'); window.location.href = '../index.php';</script>";
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        echo "<script>alert('Failed to cancel the order. Please try again.');</script>";
    }
}

// Query to fetch orders and checkout details
$sql_orders = "SELECT
                    u.username AS customer_name,
                    o.id AS order_id,
                    o.order_date,
                    o.total_amount,
                    c.cart_items,
                    o.status
                FROM tbl_user u
                INNER JOIN orders o ON u.tbl_user_id = o.tbl_user_id
                LEFT JOIN checkout c ON o.id = c.orders_id
                WHERE o.tbl_user_id = ?
                ORDER BY o.order_date DESC";

$stmt_orders = $conn->prepare($sql_orders);
$stmt_orders->bind_param('i', $tbl_user_id);
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();

// Modified query to fetch transaction history with product ID based on batch_codename prefix
$sql_history = "SELECT
    th.id,
    th.order_date,
    u.username AS customer_name,
    th.total_amount,
    th.status,
    th.cart_items,
    th.batch_codename,
    p.id as product_id
FROM transaction_history th
LEFT JOIN tbl_user u ON th.tbl_user_id = u.tbl_user_id
LEFT JOIN products p ON SUBSTRING(th.batch_codename, 1,
    LOCATE('-', th.batch_codename) - 1) = SUBSTRING(p.codename, 1,
    LOCATE('-', p.codename) - 1)
WHERE th.tbl_user_id = ?";

$stmt_history = $conn->prepare($sql_history);
$stmt_history->bind_param('i', $tbl_user_id);
$stmt_history->execute();
$result_history = $stmt_history->get_result();

// Query to fetch newly delivered orders
$sql_new_delivered = "SELECT th.id, th.order_date, th.cart_items, th.id
                      FROM transaction_history th
                      WHERE th.tbl_user_id = ? AND th.status = 'Delivered' AND th.review_requested = 0
                      ORDER BY th.order_date DESC
                      LIMIT 1";

$stmt_new_delivered = $conn->prepare($sql_new_delivered);
$stmt_new_delivered->bind_param('i', $tbl_user_id);
$stmt_new_delivered->execute();
$result_new_delivered = $stmt_new_delivered->get_result();
$new_delivered_order = $result_new_delivered->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders and Transaction History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 1200px;
            margin: 10px auto;
            padding: 10px;
            padding-top: 5px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: auto;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #007BFF;
            color: white;
            text-transform: uppercase;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e9f5ff;
        }

        td {
            color: #555;
        }

        .status {
            font-weight: bold;
            padding: 8px 12px;
            border-radius: 5px;
        }

        .status.completed {
            color: #fff;
            background-color: #28a745;
        }

        .status.pending {
            color: #fff;
            background-color: #ffc107;
        }

        .status.cancelled {
            color: #fff;
            background-color: #dc3545;
        }

        .actions {
            text-align: center;
        }

        .actions button {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            background-color: #dc3545;
            color: white;
            cursor: pointer;
            font-size: 14px;
        }

        .actions button:hover {
            background-color: #c82333;
        }
        caption {
            font-size: 1.5em;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <table>
        <caption style="caption-side: top; font-size: 1.3em; font-weight: bold; margin-bottom: 10px; text-align: left;">Order Management</caption>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Transaction Date</th>
                    <th>Customer Name</th>
                    <th>Cart Items</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_orders->num_rows > 0) {
                    // Output data for each row
                    while ($row = $result_orders->fetch_assoc()) {
                        $statusClass = strtolower($row['status']); // Convert status to lowercase for class
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['order_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['order_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['cart_items']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['total_amount']) . "</td>";
                        echo "<td><span class='status " . $statusClass . "'>" . htmlspecialchars($row['status']) . "</span></td>";
                        echo "<td class='actions'>";
                        if ($row['status'] !== 'Cancelled') {
                            echo "<form method='POST' style='display:inline;'>";
                            echo "<input type='hidden' name='cancel_order_id' value='" . htmlspecialchars($row['order_id']) . "'>";
                            echo "<button type='submit'>Cancel Order</button>";
                            echo "</form>";
                        } else {
                            echo "N/A";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No orders found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>


<div class="container">
        <table>
        <caption style="caption-side: top; font-size: 1.5em; font-weight: bold; margin-bottom: 10px; text-align: left;">Order Status</caption>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Transaction Date</th>
                    <th>Customer Name</th>
                    <th>Cart Items</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Completed</th>
                    <th>Receipt</th>
                    <th>Review</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_history->num_rows > 0) {
                    while ($row = $result_history->fetch_assoc()) {
                        $statusClass = strtolower($row['status']);
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['order_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['cart_items']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['total_amount']) . "</td>";
                        echo "<td><span class='status " . $statusClass . "'>" . htmlspecialchars($row['status']) . "</span></td>";

                        // Completed Button
                        echo "<td class='actions'>";
                        echo "<form method='POST' action='reorder.php'>";
                        echo "<input type='hidden' name='order_id' value='" . htmlspecialchars($row['id']) . "'>";
                        echo "<button type='submit' style='padding: 8px 12px; border: none; border-radius: 5px; background-color: #28a745; color: white; cursor: pointer;'>Re-order</button>";
                        echo "</form>";
                        echo "</td>";

                        // Receipt Link
                        echo "<td>";
                        echo "<a href='receipt.php?order_id=" . htmlspecialchars($row['id']) . "'
                                style='
                                    display: inline-block;
                                    padding: 8px 12px;
                                    background-color: #007BFF;
                                    color: white;
                                    text-decoration: none;
                                    border-radius: 5px;
                                    font-size: 14px;
                                    text-align: center;
                                '
                                onmouseover='this.style.backgroundColor=\"#0056b3\"'
                                onmouseout='this.style.backgroundColor=\"#007BFF\"'>
                                View Receipt
                            </a>";
                        echo "</td>";

                        // Review Button - Only show if status is Delivered and product_id exists
                        echo "<td>";
                        if ($row['status'] === 'Delivered' && $row['product_id']) {
                            echo "<a href='items.php?id=" . htmlspecialchars($row['product_id']) . "&order_id=" . htmlspecialchars($row['id']) . "#review-section'
                                    style='
                                        display: inline-block;
                                        padding: 8px 12px;
                                        background-color: #28a745;
                                        color: white;
                                        text-decoration: none;
                                        border-radius: 5px;
                                        font-size: 14px;
                                        text-align: center;
                                    '
                                    onmouseover='this.style.backgroundColor=\"#218838\"'
                                    onmouseout='this.style.backgroundColor=\"#28a745\"'>
                                    Review
                                </a>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php
    // Close the statement and database connection
    $stmt_orders->close();
    $stmt_history->close();
    $stmt_new_delivered->close();
    $conn->close();
    ?>
</body>
</html>

