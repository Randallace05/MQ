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
        /* Dropdown styling */
        .form-select {
            width: 100%;
            padding: 8px 12px;
            font-size: 16px;
            color: #555;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            appearance: none;
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4 5"%3E%3Cpath fill="%23666" d="M2 0L0 2h4z" /%3E%3C/svg%3E');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 10px;
            cursor: pointer;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        /* Hover and focus effects */
        .form-select:hover {
            border-color: #999;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .form-select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
        }

        /* Table row hover effect */
        table tbody tr:hover td {
            background-color: #f9f9f9;
        }
        /* Dropdown animation */
        .form-select {
            transition: all 0.3s ease;
        }
        .custom-width {
        width: 150px; /* Adjust the width as needed */
        }
        .status-dropdown {
        border: 1px solid #ddd;
        border-radius: 5px;
        color: #fff;
        background-color: #ccc; /* Default color */
        transition: background-color 0.3s, border-color 0.3s;
        }

        /* Status-specific colors */
        .status-order-placed {
            background-color: orange;
        }

        .status-order-shipped {
            background-color: blue;
        }

        .status-delivered {
            background-color: green;
        }

        .status-ng-cancel {
            background-color: red;
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
                    <th>Status</th>
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
                                    echo '<td>';
                                    echo '<select class="form-select status-dropdown" style="width: 150px;" data-order-id="' . htmlspecialchars($item['order_id']) . '" data-status="' . htmlspecialchars($item['status']) . '">';
                                    echo '<option value="Order Placed"' . ($item['status'] == 'Order Placed' ? ' selected' : '') . '>Order Placed</option>';
                                    echo '<option value="Order Shipped"' . ($item['status'] == 'Order Shipped' ? ' selected' : '') . '>Order Shipped</option>';
                                    echo '<option value="Delivered"' . ($item['status'] == 'Delivered' ? ' selected' : '') . '>Delivered</option>';
                                    echo '<option value="Ng Cancel"' . ($item['status'] == 'Ng Cancel' ? ' selected' : '') . '>Cancel</option>';
                                    echo '</select>';
                                    echo '</td>';
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>No transaction history found.</td></tr>";
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

        document.addEventListener('DOMContentLoaded', () => {
        const dropdowns = document.querySelectorAll('.status-dropdown');
        dropdowns.forEach(dropdown => {
            dropdown.addEventListener('change', (e) => {
                const orderId = e.target.getAttribute('data-order-id');
                const status = e.target.value;

                fetch('update_status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ order_id: orderId, status: status })
                })
                
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        const dropdowns = document.querySelectorAll('.status-dropdown');

        dropdowns.forEach(function (dropdown) {
            const currentStatus = dropdown.getAttribute('data-status');
            applyStatusClass(dropdown, currentStatus);

            // Update color on change
            dropdown.addEventListener('change', function () {
                const newStatus = dropdown.value;
                applyStatusClass(dropdown, newStatus);
            });
        });

        function applyStatusClass(element, status) {
            element.classList.remove(
                'status-order-placed',
                'status-order-shipped',
                'status-delivered',
                'status-ng-cancel'
            );

            if (status === 'Order Placed') {
                element.classList.add('status-order-placed');
            } else if (status === 'Order Shipped') {
                element.classList.add('status-order-shipped');
            } else if (status === 'Delivered') {
                element.classList.add('status-delivered');
            } else if (status === 'Ng Cancel') {
                element.classList.add('status-ng-cancel');
            }
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
    const dropdowns = document.querySelectorAll('.status-dropdown');
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('change', (e) => {
            const orderId = e.target.getAttribute('data-order-id');
            const status = e.target.value;

            fetch('update_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ order_id: orderId, status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Status updated successfully');
                } else {
                    console.error('Error updating status:', data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
});

    </script>
</body>

</html>
