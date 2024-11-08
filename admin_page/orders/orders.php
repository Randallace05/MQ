<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Orders - Dashboard</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../dashboard/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include("../includesAdmin/sidebar.php"); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include("../includesAdmin/topbar.php"); ?>
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Order</h1>
                    </div>

                    <div class="container">
                        <h2>Order List</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Menu</th>
                                    <th>Quantity</th>
                                    <th>Total Payment</th>
                                    <th>Payment</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Database connection
                                $conn = new mysqli("localhost", "username", "password", "database");

                                // Check connection
                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }

                                // Fetch orders from database
                                $sql = "SELECT id, customer, menu, quantity, price, payment_method FROM orders";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    // Output data of each row
                                    while($row = $result->fetch_assoc()) {
                                        $total_payment = $row["quantity"] * $row["price"];
                                        echo "<tr>
                                            <td class='customer-name'><img src='{$row["customer"]}.png' alt='{$row["customer"]}' class='profile-pic'> {$row["customer"]}</td>
                                            <td>{$row["menu"]}</td>
                                            <td>
                                                <input type='number' value='{$row["quantity"]}' min='1' style='width: 60px;' 
                                                       onchange='updateTotal(this, {$row["price"]}, {$row["id"]})'>
                                            </td>
                                            <td class='total'>₱ {$total_payment}</td>
                                            <td class='payment-method'>{$row["payment_method"]}</td>
                                            <td class='action-buttons'>
                                                <button class='arrange'>Arrange</button>
                                                <button class='cancel'>Cancel</button>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>No orders found</td></tr>";
                                }

                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2024</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        function updateTotal(element, price, orderId) {
            const quantity = element.value;
            const totalPayment = quantity * price;

            // Find the total payment cell for the current row and update its value
            const totalCell = element.closest("tr").querySelector(".total");
            totalCell.textContent = `₱ ${totalPayment}`;

            // Optional: Send an AJAX request to update quantity in the database
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "update_order.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send(`id=${orderId}&quantity=${quantity}`);
        }
    </script>
</body>
</html>
