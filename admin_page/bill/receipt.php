<?php
// Database connection
$host = "localhost"; // Replace with your database host
$user = "root";      // Replace with your database username
$password = "";      // Replace with your database password
$database = "login_email_verification"; // Replace with your database name

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data (e.g., the logged-in user)
$user_id = 25; // Example user_id
$user_sql = "SELECT * FROM tbl_user WHERE tbl_user_id = $user_id";
$user_result = $conn->query($user_sql);
$user = $user_result->fetch_assoc();

// Fetch checkout details
$checkout_sql = "SELECT * FROM checkout LIMIT 1";
$checkout_result = $conn->query($checkout_sql);
$checkout = $checkout_result->fetch_assoc();

// Fetch cart items
$cart_sql = "SELECT * FROM cart WHERE tbl_user_id = $user_id";
$cart_result = $conn->query($cart_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .receipt-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            color: #333;
        }
        .receipt-details, .order-summary {
            margin-bottom: 20px;
        }
        .order-summary table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-summary th, .order-summary td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .order-summary th {
            background-color: #f8f8f8;
        }
        .total {
            text-align: right;
            font-weight: bold;
            color: #333;
        }
        .button-container {
            text-align: center;
            margin: 20px 0;
        }
        .button-container button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="receipt-container" id="receipt">
        <div class="header">
            <h2>Order Receipt</h2>
            <p>Thank you for your purchase!</p>
        </div>
        <div class="receipt-details">
            <p><strong>Order Number:</strong> 123456</p>
            <p><strong>Order Date:</strong> <?php echo date('Y-m-d'); ?></p>
            <p><strong>Customer Name:</strong> <?php echo $user['first_name'] . ' ' . $user['last_name']; ?></p>
            <p><strong>Shipping Address:</strong> <?php echo $checkout['address']; ?></p>
            <p><strong>Payment Method:</strong> <?php echo $checkout['payment_method']; ?></p>
        </div>
        <div class="order-summary">
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_price = 0;
                    while ($row = $cart_result->fetch_assoc()) {
                        $item_total = $row['price'] * $row['quantity'];
                        $total_price += $item_total;
                        echo "<tr>
                                <td>{$row['name']}</td>
                                <td>{$row['quantity']}</td>
                                <td>\${$row['price']}</td>
                                <td>\${$item_total}</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
            <p class="total">Subtotal: $<?php echo number_format($total_price, 2); ?></p>
            <p class="total">Total: $<?php echo number_format($total_price * 1.08, 2); // Including 8% tax ?></p>
        </div>
        <div class="footer">
            <p>If you have any questions about your order, please contact us at support@example.com.</p>
        </div>
    </div>
    <div class="button-container">
        <button onclick="printReceipt()">Print Receipt</button>
        <button onclick="downloadPDF()">Download as PDF</button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        function printReceipt() {
            window.print();
        }

        function downloadPDF() {
            const receiptElement = document.getElementById('receipt');
            const options = {
                margin:       0.5,
                filename:     'order_receipt.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            html2pdf().set(options).from(receiptElement).save();
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
