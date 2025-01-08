<?php
// Include the database connection
include("../../conn/conn.php");

if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);

    // Fetch order details
    $sql = "SELECT tbl_user.username, orders.*, checkout.cart_items 
            FROM tbl_user 
            INNER JOIN orders ON tbl_user.tbl_user_id = orders.tbl_user_id 
            LEFT JOIN checkout ON orders.id = checkout.orders_id 
            WHERE orders.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        // Generate receipt with admin styling
        echo "
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #2c3e50;
                    color: #ecf0f1;
                    margin: 0;
                    padding: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                }
                .receipt-container {
                    max-width: 700px;
                    width: 100%;
                    background-color: #34495e;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                }
                .header {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .header h1 {
                    margin: 0;
                    font-size: 28px;
                    color: #1abc9c;
                }
                .header p {
                    margin: 5px 0;
                    color: #bdc3c7;
                }
                .details {
                    margin-bottom: 20px;
                }
                .details p {
                    margin: 5px 0;
                    line-height: 1.6;
                }
                .order-summary {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                .order-summary th, 
                .order-summary td {
                    text-align: left;
                    padding: 10px;
                    border-bottom: 1px solid #7f8c8d;
                }
                .order-summary th {
                    background-color: #1abc9c;
                    color: #fff;
                    font-weight: bold;
                }
                .totals {
                    text-align: right;
                    margin-top: 20px;
                }
                .totals p {
                    margin: 5px 0;
                    font-size: 16px;
                }
                .totals .grand-total {
                    font-size: 20px;
                    font-weight: bold;
                    color: #1abc9c;
                }
                .highlight {
                    color: #1abc9c;
                    font-weight: bold;
                }
                .buttons {
                    margin-top: 20px;
                    text-align: center;
                }
                .buttons button {
                    background-color: #1abc9c;
                    color: #fff;
                    border: none;
                    padding: 10px 15px;
                    border-radius: 5px;
                    margin: 5px;
                    cursor: pointer;
                }
                .buttons button:hover {
                    background-color: #16a085;
                }
                .buttons a {
                    text-decoration: none;
                    color: #fff;
                }
            </style>
            <script>
                function printReceipt() {
                    window.print();
                }
                function downloadReceipt() {
                    const receiptContent = document.querySelector('.receipt-container').innerHTML;
                    const blob = new Blob([receiptContent], { type: 'text/html' });
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'receipt.html';
                    link.click();
                }
            </script>
        </head>
        <body>
            <div class='receipt-container'>
                <div class='header'>
                    <h1>Order Receipt</h1>
                    <p>Thank you for using our service!</p>
                </div>
                <div class='details'>
                    <p><span class='highlight'>Customer:</span> " . htmlspecialchars($order['username']) . "</p>
                    <p><span class='highlight'>Order ID:</span> " . htmlspecialchars($order['id']) . "</p>
                    <p><span class='highlight'>Order Date:</span> " . htmlspecialchars($order['order_date']) . "</p>
                    <p><span class='highlight'>Cart Items:</span> " . htmlspecialchars($order['cart_items']) . "</p>
                    <p><span class='highlight'>Shipping Address:</span> " . htmlspecialchars($order['shipping_address']) . "</p>
                </div>
                <table class='order-summary'>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Chili Garlic Bagoong</td>
                            <td>1</td>
                            <td>₱278.00</td>
                            <td>₱278.00</td>
                        </tr>
                    </tbody>
                </table>
                <div class='totals'>
                    <p><strong>Subtotal:</strong> ₱278.00</p>
                    <p><strong>Shipping Fee:</strong> ₱60.00</p>
                    <p class='grand-total'><strong>Grand Total:</strong> ₱338.00</p>
                </div>
                <div class='details'>
                    <p><span class='highlight'>Payment Method:</span> " . htmlspecialchars($order['payment_method']) . "</p>
                </div>
                <div class='buttons'>
                    <button onclick='printReceipt()'>Print</button>
                    <button onclick='downloadReceipt()'>Download</button>
                    <button><a href='orders.php'>Back to Orders</a></button>
                </div>
            </div>
        </body>
        </html>
        ";
    } else {
        echo "Order not found.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
<script>
    function printReceipt() {
    window.print();
}


async function downloadReceipt() {
    // Select the receipt content from the DOM
    const receiptContent = document.querySelector('.receipt-container');
    
    // Import jsPDF from the global `jspdf` object
    const { jsPDF } = window.jspdf;

    // Create a new jsPDF instance
    const pdf = new jsPDF();

    // Generate the PDF using the selected HTML content
    await pdf.html(receiptContent, {
        callback: function (pdf) {
            // Save the generated PDF with the name "receipt.pdf"
            doc.save('receipt.pdf');
        },
        x: 10, // Adjust the X coordinate (horizontal margin)
        y: 10, // Adjust the Y coordinate (vertical margin)
    });
}


</script>