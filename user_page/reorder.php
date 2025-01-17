<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../conn/conn.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "<script>
        alert('You need to log in to reorder items.');
        window.location.href = '../index.php';
    </script>";
    exit;
}

// Check if order_id is passed via POST request
if (isset($_POST['order_id'])) {
    $orderId = $_POST['order_id']; // Get the order_id from the form

    try {
        // Fetch cart_items from the transaction_history using the order ID
        $stmt = $conn->prepare("SELECT cart_items FROM transaction_history WHERE id = ?");
        $stmt->bind_param("i", $orderId); // Bind the integer order_id to the placeholder
        $stmt->execute();
        $result = $stmt->get_result();
        $transaction = $result->fetch_assoc();

        if (!$transaction) {
            die("Transaction not found.");
        }

        // Debug: Output the raw cart_items value
        $cartItemsString = $transaction['cart_items'];
        echo "Raw cart_items: " . $cartItemsString; // Check what we're getting from DB

        // Split the cart_items string by comma or any delimiter you're using to separate the products
        $cartItemsArray = explode(",", $cartItemsString); // Split string into array by comma (or other delimiter)

        if (empty($cartItemsArray)) {
            die("No items in the transaction to reorder.");
        }

        // Get the logged-in user's ID (assuming it's stored in session)
        $tbl_user_id = $_SESSION['tbl_user_id']; // Replace this with your actual session variable for user ID

        // Loop through the cart items and insert them into the cart table
        foreach ($cartItemsArray as $cartItem) {
            $cartItem = trim($cartItem); // Trim any extra spaces

            // Remove quantity info if it's part of the cart item string (e.g., "Chili Garlic Bagoong (1x)")
            // This pattern removes the quantity part, leaving just the product name
            $cartItemName = preg_replace('/\s*\(\d+x\)\s*/', '', $cartItem);

            // Fetch product details from the products table using the cleaned-up product name
            $productStmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE name = ?");
            $productStmt->bind_param("s", $cartItemName); // Bind the cleaned-up product name
            $productStmt->execute();
            $productResult = $productStmt->get_result();
            $product = $productResult->fetch_assoc();

            if (!$product) {
                echo "Product '$cartItemName' not found in the products table. Skipping.<br>";
                continue; // Skip this item if it's not found in the database
            }

            // Assuming the quantity is always 1 (you can adjust this based on your format in cart_items)
            $productId = $product['id'];
            $quantity = 1;
            $price = $product['price'];
            $image = $product['image'];
            $totalPrice = $price * $quantity;

            // Insert the product into the cart table (no need to include cart_id)
            $cartStmt = $conn->prepare(
                "INSERT INTO cart (product_id, name, price, image, quantity, total_price, tbl_user_id)
                VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $cartStmt->bind_param("isssidi", $productId, $cartItemName, $price, $image, $quantity, $totalPrice, $tbl_user_id);
            $cartStmt->execute();

            // Check if the insert was successful
            if ($cartStmt->affected_rows > 0) {
                echo "Inserted product: $cartItemName successfully.<br>";
            } else {
                echo "Failed to insert product: $cartItemName.<br>";
            }
        }

        // Redirect to the cart.php page after successful insertion
        header("Location: cart.php");
        exit; // Ensure the script stops after header redirect to stop further script execution

    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
