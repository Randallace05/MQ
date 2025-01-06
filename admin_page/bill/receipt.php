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
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
$tbl_user_id = intval($_SESSION['unique_id']); // Example user ID
$user_sql = "SELECT firstname, lastname FROM checkout WHERE tbl_user_id = $tbl_user_id";
$user_result = $conn->query($user_sql);
if ($user_result && $user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
} else {
    $user = ['first_name' => 'Guest', 'last_name' => 'User']; // Default values
}

// Fetch checkout details
$checkout_sql = "SELECT address, payment_method FROM checkout WHERE tbl_user_id = $tbl_user_id";
$checkout_result = $conn->query($checkout_sql);
if ($checkout_result && $checkout_result->num_rows > 0) {
    $checkout = $checkout_result->fetch_assoc();
} else {
    $checkout = ['address' => 'No Address Provided', 'payment_method' => 'Not Specified']; // Default values
}

// Fetch cart items and calculate total
$cart_sql = "SELECT name, price, quantity FROM cart WHERE tbl_user_id = $tbl_user_id";
$cart_result = $conn->query($cart_sql);
if (!$cart_result || $cart_result->num_rows == 0) {
    die("No items in the cart for this user.");
}

$subtotal = 0;
$shipping_fee = 60; // Fixed shipping fee
while ($row = $cart_result->fetch_assoc()) {
    $subtotal += $row['price'] * $row['quantity'];
}
$grand_total = $subtotal + $shipping_fee;

// Insert order into the database
$order_sql = "INSERT INTO orders (tbl_user_id, total_amount, shipping_address, payment_method)
              VALUES ($tbl_user_id, $grand_total, '{$checkout['address']}', '{$checkout['payment_method']}')";
if ($conn->query($order_sql) === TRUE) {
    $order_id = $conn->insert_id; // Get the auto-incremented order ID
} else {
    die("Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt</title>
    <style>
        /* Styles for the receipt */
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
            <p><strong>Order Number:</strong> <?php echo $order_id; ?></p>
            <p><strong>Order Date:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
            <p><strong>Customer Name:</strong> <?php echo $user['firstname'] . ' ' . $user['lastname']; ?></p>
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
                    $cart_result = $conn->query($cart_sql); // Reset the cart query
                    while ($row = $cart_result->fetch_assoc()) {
                        $item_total = $row['price'] * $row['quantity'];
                        echo "<tr>
                                <td>{$row['name']}</td>
                                <td>{$row['quantity']}</td>
                                <td>₱" . number_format($row['price'], 2) . "</td>
                                <td>₱" . number_format($item_total, 2) . "</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
            <p class="total">Subtotal: ₱<?php echo number_format($subtotal, 2); ?></p>
            <p class="total">Shipping Fee: ₱<?php echo number_format($shipping_fee, 2); ?></p>
            <p class="total">Grand Total: ₱<?php echo number_format($grand_total, 2); ?></p>
        </div>
        <div class="footer">
            <p>If you have any questions about your order, please contact us at mqsupport@gmail.com.</p>
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
