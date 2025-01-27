<?php
include("../../conn/conn.php");

function fetchInventory($conn, $limit, $offset) {
    $sql = "
        SELECT th.order_id, th.tbl_user_id, th.order_date, th.shipping_address, th.total_amount, th.payment_method, th.cart_items, th.status, th.batch_codename, u.username
        FROM transaction_history th
        LEFT JOIN tbl_user u ON th.tbl_user_id = u.tbl_user_id
        ORDER BY th.order_date DESC
        LIMIT $limit OFFSET $offset
    ";
    $result = $conn->query($sql);

    if (!$result) {
        die("Query Error: " . $conn->error);
    }

    $inventory = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $inventory[] = $row;
        }
    }
    return $inventory;
}

function getTotalRecords($conn) {
    $sql = "SELECT COUNT(*) AS total FROM transaction_history";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

// Pagination parameters
$limit = 10; // Items per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$totalRecords = getTotalRecords($conn);
$totalPages = ceil($totalRecords / $limit);

$inventory = fetchInventory($conn, $limit, $offset);

$conn->close();
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
            font-family: 'Arial', sans-serif;
            background-color: #eef2f5;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 85%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            text-align: center;
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            font-size: 16px;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #eaf1f8;
            transition: background-color 0.3s;
        }

        /* Dropdown Styling */
        .form-select {
            width: 110%;
            padding: 8px 10px;
            font-size: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-select:hover {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.2);
        }

        .form-select:focus {
            border-color: #0056b3;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 86, 179, 0.5);
        }

        /* Status-specific colors */
        .status-order-placed {
            background-color: orange;
            color: #fff;
        }

        .status-order-shipped {
            background-color: blue;
            color: #fff;
        }

        .status-delivered {
            background-color: green;
            color: #fff;
        }

        .status-ng-cancel {
            background-color: red;
            color: #fff;
        }

        /* Button for status */
        .status-dropdown {
            text-align: center;
            font-size: 13px;
            color: #fff;
            font-weight: 600;
            padding: 6px 8px;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Empty Table Message */
        table tbody tr td[colspan] {
            text-align: center;
            color: #999;
            font-weight: 600;
            padding: 20px;
        }
        .container .pagination {
            display: flex;
            color:#0056b3;
            justify-content: center;
            margin: 20px 0;
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
                    <div class="container">
    <h4>Order History</h4>
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
                <th>Batch Codename</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($inventory)): ?>
                <?php foreach ($inventory as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['order_id']); ?></td>
                        <td><?= htmlspecialchars($item['username']); ?></td>
                        <td><?= htmlspecialchars($item['order_date']); ?></td>
                        <td><?= htmlspecialchars($item['shipping_address']); ?></td>
                        <td>₱ <?= number_format($item['total_amount'], 2); ?></td>
                        <td><?= htmlspecialchars($item['payment_method']); ?></td>
                        <td><?= htmlspecialchars($item['cart_items']); ?></td>
                        <td><?= htmlspecialchars($item['batch_codename']); ?></td>
                        <td>
                            <select
                                class="form-select status-dropdown"
                                data-order-id="<?= htmlspecialchars($item['order_id']); ?>"
                                data-status="<?= htmlspecialchars($item['status']); ?>"
                            >
                                <option value="Order Placed" <?= $item['status'] == 'Order Placed' ? 'selected' : ''; ?>>Placed</option>
                                <option value="Order Shipped" <?= $item['status'] == 'Order Shipped' ? 'selected' : ''; ?>>Shipped</option>
                                <option value="Delivered" <?= $item['status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                <option value="Ng Cancel" <?= $item['status'] == 'Ng Cancel' ? 'selected' : ''; ?>>Cancel</option>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No transaction history found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1; ?>" class="btn btn-primary">Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i; ?>" class="btn <?= $i == $page ? 'btn-secondary' : 'btn-primary'; ?>"><?= $i; ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1; ?>" class="btn btn-primary">Next</a>
        <?php endif; ?>
    </div>
</div>
</body>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
    const dropdowns = document.querySelectorAll('.status-dropdown');

    dropdowns.forEach(dropdown => {
        // Get default status and apply style
        const status = dropdown.getAttribute('data-status');
        applyStatusClass(dropdown, status);

        // Update style and send API request on change
        dropdown.addEventListener('change', (e) => {
            const newStatus = e.target.value;
            applyStatusClass(dropdown, newStatus);

            // API request to update status
            fetch('update_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    order_id: e.target.getAttribute('data-order-id'),
                    status: newStatus
                }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Status updated successfully');
                    } else {
                        console.error('Error updating status:', data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });

    // Function to apply status-specific styles
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
    </script>
</html>

