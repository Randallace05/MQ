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
            <p><strong>Order Date:</strong> 2024-11-09</p>
            <p><strong>Customer Name:</strong> John Doe</p>
            <p><strong>Shipping Address:</strong> 123 Main St, Springfield, IL 62701</p>
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
                    <tr>
                        <td>Product 1</td>
                        <td>2</td>
                        <td>$25.00</td>
                        <td>$50.00</td>
                    </tr>
                    <tr>
                        <td>Product 2</td>
                        <td>1</td>
                        <td>$15.00</td>
                        <td>$15.00</td>
                    </tr>
                    <tr>
                        <td>Product 3</td>
                        <td>3</td>
                        <td>$10.00</td>
                        <td>$30.00</td>
                    </tr>
                </tbody>
            </table>
            <p class="total">Subtotal: $95.00</p>
            <p class="total">Tax (8%): $7.60</p>
            <p class="total">Total: $102.60</p>
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
