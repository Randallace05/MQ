<?php
// Include the database connection
include("../../conn/conn.php");

function fetchInventory($conn) {
    $sql = "SELECT * FROM transaction_history ORDER BY order_date DESC";
    $result = $conn->query($sql);

    if (!$result) {
        die("Query Error: " . $conn->error); // Debugging query errors
    }

    $inventory = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $inventory[] = $row;
        }
    }
    return $inventory;
}

$inventory = fetchInventory($conn);

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

    <title>Order History</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../dashboard/css/sb-admin-2.min.css" rel="stylesheet">
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
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include("../includesAdmin/sidebar.php"); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include("../includesAdmin/topbar.php"); ?>
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between mb-2">
<body>
    <div class="container">
        <h1>Order History</h1>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Order Date</th>
                    <th>Shipping Address</th>
                    <th>Total Amount</th>
                    <th>Payment Method</th>
                    <th>Cart Items</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($inventory)) {
                    foreach ($inventory as $item) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($item['order_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($item['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($item['order_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($item['shipping_address']) . "</td>";
                        echo "<td>₱ " . number_format($item['total_amount'], 2) . "</td>";
                        echo "<td>" . htmlspecialchars($item['payment_method']) . "</td>";
                        echo "<td>" . htmlspecialchars($item['cart_items']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No transaction history found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script>
        // JavaScript function to handle sorting
        function sortInventory() {
            const sortOrder = document.getElementById('sortDropdown').value;
            window.location.href = '?sort=' + sortOrder;
        }

        // JavaScript function to handle "Add Stock" button click
        function addStock(productId) {
            alert('Add stock functionality for Product ID: ' + productId);
            // Implement your add stock logic here, such as opening a modal or making an AJAX call
        }
    </script>
</body>

</html>
