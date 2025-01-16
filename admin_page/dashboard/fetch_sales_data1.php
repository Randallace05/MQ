<?php
// Database connection
$servername = "localhost"; // Change to your DB server
$username = "root"; // Change to your DB username
$password = ""; // Change to your DB password
$dbname = "login_email_verification"; // Change to your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$sql = "SELECT cart_items FROM transaction_history";
$result = $conn->query($sql);

$productCounts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cartItems = explode(",", $row["cart_items"]); // Split cart_items by ","
        foreach ($cartItems as $item) {
            preg_match("/(.+?)\\((\\d+)x\\)/", trim($item), $matches); // Extract product name and quantity
            if (count($matches) === 3) {
                $productName = trim($matches[1]);
                $quantity = (int) $matches[2];
                if (!isset($productCounts[$productName])) {
                    $productCounts[$productName] = 0;
                }
                $productCounts[$productName] += $quantity;
            }
        }
    }
}

$conn->close();

// Send data as JSON
echo json_encode($productCounts);
?>
