<?php
// Include the database connection
include("../../conn/conn.php");

function fetchOrders($conn) {
    // Adjust the SQL query to fetch data correctly
    $sql = "SELECT
                tbl_user.username,
                orders.id AS order_id, -- Ensure 'id' exists in the orders table
                orders.order_date,
                orders.total_amount,
                orders.shipping_address,
                orders.payment_method,
                checkout.cart_items
            FROM
                tbl_user
            INNER JOIN orders
                ON tbl_user.tbl_user_id = orders.tbl_user_id
            LEFT JOIN checkout
                ON orders.id = checkout.orders_id -- Ensure 'orders_id' exists in the checkout table
            ORDER BY orders.order_date DESC";

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

$conn->close(); // Close the database connection
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Orders - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../dashboard/css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* Custom styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            color: white;
            font-size: 14px;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-danger {
            background-color: #dc3545;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include("../includesAdmin/sidebar.php"); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include("../includesAdmin/topbar.php"); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Orders</h1>
                    </div>

                    <div class="container">
                        <h2>Order List</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Order ID</th>
                                    <th>Cart Items</th>
                                    <th>Order Date</th>
                                    <th>Shipping Address</th>
                                    <th>Total Amount</th>
                                    <th>Payment Method</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($orders)) {
                                    foreach ($orders as $order) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($order['username']) . "</td>";
                                        echo "<td>" . htmlspecialchars($order['order_id']) . "</td>";
                                        echo "<td>" . htmlspecialchars($order['cart_items']) . "</td>";
                                        echo "<td>" . htmlspecialchars($order['order_date']) . "</td>";
                                        echo "<td>" . htmlspecialchars($order['shipping_address']) . "</td>";
                                        echo "<td>₱ " . number_format($order['total_amount'], 2) . "</td>";
                                        echo "<td>" . htmlspecialchars($order['payment_method']) . "</td>";
                                        echo "<td>";
                                        echo "<a href='arrange_order.php?order_id=" . urlencode($order['order_id']) . "' class='btn btn-primary'>Arrange Order</a> ";
                                        echo "<a href='delete_order.php?order_id=" . urlencode($order['order_id']) . "' class='btn btn-danger'>Cancel</a>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8'>No orders found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
