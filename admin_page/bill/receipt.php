<?php
include("../../conn/conn.php");

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
$unique_id = intval($_SESSION['tbl_user_id']);

// Fetch the latest checkout record for the user
$checkout_sql = "SELECT checkout_id, firstname, middlename, lastname, address, city, zip_code, contact_number, payment_method, cart_items
                 FROM checkout
                 WHERE tbl_user_id = $unique_id
                 ORDER BY checkout_id DESC
                 LIMIT 1";
$checkout_result = $conn->query($checkout_sql);

if ($checkout_result && $checkout_result->num_rows > 0) {
    $user = $checkout_result->fetch_assoc();
    $checkout_id = $user['checkout_id']; // Use `checkout_id` as the unique identifier

    // Parse the `cart_items` field manually (assuming it's a plain string)
    $cart_items_raw = $user['cart_items'];
    $cart_items = [];

    // Split items by comma (if multiple items exist)
    $items = explode(',', $cart_items_raw);
    foreach ($items as $item) {
        // Use regex to extract item name and quantity
        if (preg_match('/^(.*?)\s\((\d+)x\)$/', trim($item), $matches)) {
            $cart_items[] = [
                'name' => trim($matches[1]), // Item name
                'quantity' => intval($matches[2]), // Quantity
            ];
        }
    }

    // Check if cart items were parsed
    if (empty($cart_items)) {
        die("Cart items could not be parsed. Please ensure the format is correct.");
    }
} else {
    die("No checkout details found for this user.");
}

// Initialize variables
$subtotal = 0;
$shipping_fee = 60; // Fixed shipping fee

// Prepare an array for item details
$cart_details = [];

// Fetch prices for each item from the database
foreach ($cart_items as $item) {
    $name = $conn->real_escape_string($item['name']);
    $quantity = intval($item['quantity']);

    // Fetch item price
    $price_sql = "SELECT price FROM products WHERE name = '$name' LIMIT 1";
    $price_result = $conn->query($price_sql);

    if ($price_result && $price_result->num_rows > 0) {
        $product = $price_result->fetch_assoc();
        $price = $product['price'];
        $item_total = $price * $quantity;

        // Add to cart details
        $cart_details[] = [
            'name' => $name,
            'quantity' => $quantity,
            'price' => $price,
            'total' => $item_total,
        ];

        // Calculate subtotal
        $subtotal += $item_total;
    } else {
        die("Price not found for item: $name");
    }
}

$grand_total = $subtotal + $shipping_fee;

// Prepare shipping address
$shipping_address = "{$user['address']}, {$user['city']}, {$user['zip_code']}";
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
                    foreach ($cart_details as $detail) {
                        echo "<tr>
                                <td>{$detail['name']}</td>
                                <td>{$detail['quantity']}</td>
                                <td>₱" . number_format($detail['price'], 2) . "</td>
                                <td>₱" . number_format($detail['total'], 2) . "</td>
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
        <button class="print-btn" onclick="window.print()">Print</button>
        <button id="download-btn">Download PDF</button>
        <a href="../../user_page/shop.php" class="back-btn">Back to Shop</a>
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script>
    document.getElementById('download-btn').addEventListener('click', function () {
        const { jsPDF } = window.jspdf;
        const receipt = document.querySelector('.receipt-container');

        html2canvas(receipt, { scale: 2 })
            .then((canvas) => {
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jsPDF();
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

                pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                pdf.save('receipt.pdf');
            })
            .catch((err) => {
                console.error('Error generating PDF:', err);
            });
    });
</script>
</html>

<?php
$conn->close();
?>