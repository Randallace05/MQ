<?php
// Include the database connection
include("../../conn/conn.php");




function fetchOrders($conn) {
    $sql = "SELECT
                username, 
                name, 
                price, 
                quantity, 
                (quantity * price) AS total_price, 
                quantity, 
                cart_id
            FROM 
                tbl_user
            INNER JOIN cart 
            ON tbl_user.tbl_user_id = cart.tbl_user_id";

    $result = $conn->query($sql);

    if (!$result) {
        die("Query Error: " . $conn->error); // Show the error
    }

    $orders = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    }
    return $orders;
}


$orders = fetchOrders($conn);


if (empty($orders)) {
    echo "No orders found!";
} else {
    echo "<pre>";
    print_r($orders);
    echo "</pre>";
}



$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
</head>
<body>
    <h1>Order List</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Items</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Reference Number</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($orders)) {
                foreach ($orders as $order) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($order['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($order['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($order['quantity']) . "</td>";
                    echo "<td>₱ " . number_format($order['total_price'], 2) . "</td>";
                    echo "<td>" . htmlspecialchars($order['cart_id']) . "</td>";
                    echo "<td>";
                    echo "<a href='arrange_order.php?cart_id=" . urlencode($order['cart_id']) . "' class='btn btn-arrange'>Arrange Order</a> ";
                    echo "<a href='cancel_order.php?cart_id=" . urlencode($order['cart_id']) . "' class='btn btn-cancel'>Cancel Order</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No orders found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
