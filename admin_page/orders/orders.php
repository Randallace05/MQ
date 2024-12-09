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

        .btn-arrange {
            background-color: #5cb85c;
        }

        .btn-cancel {
            background-color: #d9534f;
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
                        <h1 class="h3 mb-0 text-gray-800">Order</h1>
                    </div>

                    <div class="container">
                        <h2>Order List</h2>
                        <table>
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
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>

<?php
$conn->close();
?>
