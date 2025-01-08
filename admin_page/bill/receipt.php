<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "login_email_verification";

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
        window.location.href = '../index.php';
    </script>";
    exit;
}

// Get the logged-in user's unique ID
$unique_id = intval($_SESSION['unique_id']);

// Fetch the latest checkout record for the user
$checkout_sql = "SELECT checkout_id, firstname, middlename, lastname, address, city, zip_code, contact_number, payment_method
                 FROM checkout
                 WHERE tbl_user_id = $unique_id
                 ORDER BY checkout_id DESC
                 LIMIT 1";
$checkout_result = $conn->query($checkout_sql);

if ($checkout_result && $checkout_result->num_rows > 0) {
    $user = $checkout_result->fetch_assoc();
    $checkout_id = $user['checkout_id']; // Use `checkout_id` as the unique identifier
} else {
    die("No checkout details found for this user.");
}

// Fetch cart items
// Fetch cart items and store in an array
$cart_items = [];
$cart_sql = "SELECT name, price, quantity FROM cart WHERE tbl_user_id = $unique_id";
$cart_result = $conn->query($cart_sql);

if ($cart_result && $cart_result->num_rows > 0) {
    while ($row = $cart_result->fetch_assoc()) {
        $cart_items[] = $row;
    }
} else {
    die("No items in the cart for this user.");
}

// Calculate totals
$subtotal = 0;
$shipping_fee = 60; // Fixed shipping fee
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$grand_total = $subtotal + $shipping_fee;

// Prepare shipping address
$shipping_address = "{$user['address']}, {$user['city']}, {$user['zip_code']}";

// Clear the cart
$clear_cart_sql = "DELETE FROM cart WHERE tbl_user_id = $unique_id";

if ($conn->query($clear_cart_sql) === TRUE) {
    echo "<script>console.log('Cart cleared successfully.')</script>";
} else {
    echo "<script>console.error('Error clearing the cart: " . $conn->error . "');</script>";
    die("Error clearing the cart: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt</title>
    <style>
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #eef2f7;
    color: #444;
    padding: 20px;
    line-height: 1.6;
    margin: 0;
}

.receipt-container {
    max-width: 600px;
    margin: 0 auto;
    background-color: #ffffff;
    padding: 20px 25px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border: 1px solid #dfe3e8;
}

.header {
    text-align: center;
    margin-bottom: 25px;
    color: #2c3e50;
}

.header h2 {
    font-size: 1.8em;
    margin: 0;
}

.header p {
    font-size: 1em;
    color: #6c757d;
    margin: 5px 0 0;
}

.order-summary {
    margin-top: 20px;
}

.order-summary table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.order-summary th, .order-summary td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #e8ecef;
}

.order-summary th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #333;
}

.order-summary td {
    color: #555;
}

.total {
    text-align: right;
    font-size: 1.2em;
    font-weight: bold;
    color: #2c3e50;
    margin-top: 10px;
}

.actions {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 25px;
}

.actions button, .actions .back-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-size: 1em;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    color: #fff;
    transition: background-color 0.3s ease;
    flex: 1 1 auto;
    max-width: 160px;
}

.actions button {
    background-color: #007bff;
}

.actions button:hover {
    background-color: #0056b3;
}

.actions .back-btn {
    background-color: #28a745;
}

.actions .back-btn:hover {
    background-color: #218838;
}

/* Media Queries for Responsiveness */
@media (max-width: 768px) {
    .receipt-container {
        padding: 15px;
    }

    .header h2 {
        font-size: 1.5em;
    }

    .header p {
        font-size: 0.9em;
    }

    .order-summary th, .order-summary td {
        padding: 10px 8px;
    }

    .actions button, .actions .back-btn {
        font-size: 0.9em;
        padding: 8px 15px;
    }
}

@media (max-width: 480px) {
    .header h2 {
        font-size: 1.2em;
    }

    .header p {
        font-size: 0.8em;
    }

    .total {
        font-size: 1em;
    }

    .actions {
        flex-direction: column;
        gap: 10px;
    }

    .actions button, .actions .back-btn {
        max-width: 100%;
        padding: 10px;
    }
}


    </style>
</head>
<body>
    <div class="receipt-container" id="receipt">
        <div class="header">
            <h2>Order Receipt</h2>
            <p>Thank you for your purchase!</p>
        </div>
        <div class="details">
            <p><strong>Order Number:</strong> <?php echo $checkout_id; ?></p>
            <p><strong>Customer Name:</strong> <?php echo "{$user['firstname']} {$user['middlename']} {$user['lastname']}"; ?></p>
            <p><strong>Shipping Address:</strong> <?php echo $shipping_address; ?></p>
            <p><strong>Phone Number:</strong> <?php echo $user['contact_number']; ?></p>
            <p><strong>Payment Method:</strong> <?php echo $user['payment_method']; ?></p>
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
                    foreach ($cart_items as $item) {
                        $item_total = $item['price'] * $item['quantity'];
                        echo "<tr>
                                <td>{$item['name']}</td>
                                <td>{$item['quantity']}</td>
                                <td>₱" . number_format($item['price'], 2) . "</td>
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
    </div>
    <div class="actions">
        <button id="print-btn">Print</button>
        <button id="download-btn">Download PDF</button>
        <a href="user_page/shop.php" class="back-btn">Back to Shop</a>
    </div>
</div>
</body>
</html>

<?php
$conn->close();
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script>
    // Print Functionality
    document.getElementById('print-btn').addEventListener('click', function () {
        window.print();
    });

    // Download PDF Functionality
    document.getElementById('download-btn').addEventListener('click', function () {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Capture the receipt container
        const receipt = document.querySelector('.receipt-container');
        doc.html(receipt, {
            callback: function (doc) {
                doc.save('receipt.pdf');
            },
            x: 10,
            y: 10
        });
    });
</script>
