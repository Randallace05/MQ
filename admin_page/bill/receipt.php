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
$cart_sql = "SELECT name, price, quantity FROM cart WHERE tbl_user_id = $unique_id";
$cart_result = $conn->query($cart_sql);

if (!$cart_result || $cart_result->num_rows == 0) {
    die("No items in the cart for this user.");
}

// Calculate totals
$subtotal = 0;
$shipping_fee = 60; // Fixed shipping fee
while ($row = $cart_result->fetch_assoc()) {
    $subtotal += $row['price'] * $row['quantity'];
}
$grand_total = $subtotal + $shipping_fee;

// Prepare shipping address
$shipping_address = "{$user['address']}, {$user['city']}, {$user['zip_code']}";

// Check if an order already exists
$order_check_sql = "SELECT checkout_id
                    FROM checkout
                    WHERE checkout_id = $checkout_id";
$order_check_result = $conn->query($order_check_sql);

if ($order_check_result && $order_check_result->num_rows > 0) {
    // Fetch existing order ID
    $order_row = $order_check_result->fetch_assoc();
    $order_id = $order_row['checkout_id'];
} else {
    // Insert a new order into the `orders` table
    $order_sql = "INSERT INTO orders (tbl_user_id, checkout_id, total_amount, shipping_address, payment_method)
                  VALUES ($unique_id, $checkout_id, $grand_total, '$shipping_address', '{$user['payment_method']}')";
    if ($conn->query($order_sql) === TRUE) {
        $order_id = $conn->insert_id; 
    } else {
        die("Error inserting order: " . $conn->error);
    }
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
        .order-summary table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-summary th, .order-summary td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .total {
            text-align: right;
            font-weight: bold;
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
            <p><strong>Order Number:</strong> <?php echo $order_id; ?></p>
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
                    // Reset cart result pointer
                    $cart_result = $conn->query($cart_sql);
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
    </div>
</body>
</html>

<?php
$conn->close();
?>
