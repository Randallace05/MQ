<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['error' => 'You must log in to proceed.']);
    exit;
}

// Include database connection
require_once '../../conn/conn.php';

try {
    $userId = $_SESSION['tbl_user_id'];
    $cartItems = [];
    $grandTotal = 0;

    // Handle selected products
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_products']) && !empty($_POST['selected_products'])) {
        $selected_products = array_map('intval', $_POST['selected_products']);

        // Prepare query to fetch selected cart items
        $placeholders = implode(',', array_fill(0, count($selected_products), '?'));
        $stmt = $conn->prepare("SELECT c.*, p.name FROM cart c JOIN products p ON c.product_id = p.id WHERE c.tbl_user_id = ? AND c.cart_id IN ($placeholders)");

        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }

        // Bind parameters dynamically
        $types = str_repeat('i', count($selected_products) + 1);
        $stmt->bind_param($types, $userId, ...$selected_products);

        if (!$stmt->execute()) {
            throw new Exception("Error executing statement: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $cartItems = $result->fetch_all(MYSQLI_ASSOC);

        // Calculate the total amount for selected items
        foreach ($cartItems as $item) {
            $grandTotal += $item['price'] * $item['quantity'];
        }
    } else {
        throw new Exception("No items selected. Please go back and select items to proceed.");
    }

    // Handle form submission for checkout
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['firstname'])) {
        // Validate and sanitize input
        $firstName = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
        $middleName = filter_input(INPUT_POST, 'Mname', FILTER_SANITIZE_STRING);
        $lastName = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
        $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
        $zipCode = filter_input(INPUT_POST, 'z', FILTER_SANITIZE_STRING);
        $contactNumber = filter_input(INPUT_POST, 'num', FILTER_SANITIZE_STRING);
        $paymentMethod = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_STRING);

        // Validate required fields
        if (!$firstName || !$lastName || !$address || !$city || !$zipCode || !$contactNumber || !$paymentMethod) {
            throw new Exception("All required fields must be filled out.");
        }

        // Handle Gcash payment proof upload
        $gcashProofPath = null;
        if ($paymentMethod === 'Gcash Payment' && isset($_FILES['gcash_proof']) && $_FILES['gcash_proof']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../uploads/payment_proofs/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = uniqid() . '_' . basename($_FILES['gcash_proof']['name']);
            $uploadFile = $uploadDir . $fileName;

            if (!move_uploaded_file($_FILES['gcash_proof']['tmp_name'], $uploadFile)) {
                throw new Exception("Error uploading GCash payment proof. Please try again.");
            }
            $gcashProofPath = $fileName;
        }

        // Start a transaction
        $conn->begin_transaction();

        try {
            // Insert data into the checkout table
            $stmt = $conn->prepare("
                INSERT INTO checkout (tbl_user_id, firstname, middlename, lastname, address, city, zip_code, contact_number, payment_method, gcash_proof)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param(
                "isssssssss",
                $userId,
                $firstName,
                $middleName,
                $lastName,
                $address,
                $city,
                $zipCode,
                $contactNumber,
                $paymentMethod,
                $gcashProofPath
            );
            
            if (!$stmt->execute()) {
                throw new Exception("Error inserting into checkout table: " . $stmt->error);
            }

            $order_id = $conn->insert_id;

            // Insert order items and update product batches
            $order_items_stmt = $conn->prepare("INSERT INTO order_items (orders_id, product_id, quantity, price, batch_codename) VALUES (?, ?, ?, ?, ?)");

            foreach ($cartItems as $item) {
                $order_items_stmt->bind_param("iiids", $order_id, $item['product_id'], $item['quantity'], $item['price'], $item['batch_codename']);
                if (!$order_items_stmt->execute()) {
                    throw new Exception("Error inserting order item: " . $order_items_stmt->error);
                }
            }

            // Remove processed items from the cart
            $delete_stmt = $conn->prepare("DELETE FROM cart WHERE tbl_user_id = ? AND cart_id IN ($placeholders)");
            $delete_stmt->bind_param($types, $userId, ...$selected_products);
            if (!$delete_stmt->execute()) {
                throw new Exception("Error deleting items from cart: " . $delete_stmt->error);
            }

            // Commit the transaction
            $conn->commit();

            // Redirect based on payment method
            if ($paymentMethod === "Cash on Delivery") {
                header("Location: receipt.php?order_id=$order_id");
            } elseif ($paymentMethod === "Gcash Payment") {
                header("Location: ref.php?order_id=$order_id");
            }
            exit;
        } catch (Exception $e) {
            // An error occurred, rollback the transaction
            $conn->rollback();
            throw $e;
        }
    }
} catch (Exception $e) {
    // Log the error
    error_log("Checkout Error: " . $e->getMessage());
    
    // Return a JSON response with the error message
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
    exit;
} finally {
    if (isset($conn)) {
        $conn->close(); // Close the database connection
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Check Out</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Check Out</h1>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Billing Information</h2>
                <form id="checkoutForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                    <div class="grid gap-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="fname" class="block mb-1">First Name</label>
                                <input type="text" id="fname" name="firstname" class="w-full px-3 py-2 border rounded" required>
                            </div>
                            <div>
                                <label for="Mname" class="block mb-1">Middle Name</label>
                                <input type="text" id="Mname" name="Mname" class="w-full px-3 py-2 border rounded">
                            </div>
                        </div>
                        <div>
                            <label for="lname" class="block mb-1">Last Name</label>
                            <input type="text" id="lname" name="lname" class="w-full px-3 py-2 border rounded" required>
                        </div>
                        <div>
                            <label for="address" class="block mb-1">Address</label>
                            <input type="text" id="address" name="address" class="w-full px-3 py-2 border rounded" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="city" class="block mb-1">City</label>
                                <input type="text" id="city" name="city" class="w-full px-3 py-2 border rounded" required>
                            </div>
                            <div>
                                <label for="z" class="block mb-1">Zip Code</label>
                                <input type="text" id="z" name="z" class="w-full px-3 py-2 border rounded" required>
                            </div>
                        </div>
                        <div>
                            <label for="num" class="block mb-1">Contact Number</label>
                            <input type="text" id="num" name="num" class="w-full px-3 py-2 border rounded" required pattern="\d{11}" title="Contact number must be exactly 11 digits.">
                        </div>
                        <div>
                            <label class="block mb-1">Payment Method</label>
                            <div>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="payment_method" value="Cash on Delivery" class="form-radio" required onclick="togglePaymentFields(false)">
                                    <span class="ml-2">Cash on Delivery</span>
                                </label>
                            </div>
                            <div>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="payment_method" value="Gcash Payment" class="form-radio" required onclick="togglePaymentFields(true)">
                                    <span class="ml-2">GCash Payment</span>
                                </label>
                            </div>
                        </div>
                        <div id="gcash-upload" style="display:none;">
                            <label for="gcash-proof" class="block mb-1">Upload GCash Payment Proof</label>
                            <input type="file" id="gcash-proof" name="gcash_proof" accept="image/*" class="w-full px-3 py-2 border rounded">
                        </div>
                    </div>
                    <?php foreach ($selected_products as $product_id): ?>
                        <input type="hidden" name="selected_products[]" value="<?php echo htmlspecialchars($product_id); ?>">
                    <?php endforeach; ?>
                    <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Continue to checkout</button>
                </form>
            </div>
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                <?php
                $shippingFee = 60; // Fixed shipping fee
                $totalPrice = 0;
                foreach ($cartItems as $item) {
                    $itemTotal = $item['price'] * $item['quantity'];
                    $totalPrice += $itemTotal;
                ?>
                    <div class="flex justify-between mb-2">
                        <span><?php echo htmlspecialchars($item['name']); ?></span>
                        <span>₱<?php echo number_format($itemTotal, 2); ?></span>
                    </div>
                <?php } ?>
                <div class="flex justify-between mb-2">
                    <span>Shipping Fee</span>
                    <span>₱<?php echo number_format($shippingFee, 2); ?></span>
                </div>
                <?php $totalPrice += $shippingFee; ?>
                <div class="border-t pt-2 mt-2">
                    <div class="flex justify-between font-semibold">
                        <span>Total</span>
                        <span>₱<?php echo number_format($totalPrice, 2); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePaymentFields(show) {
            const gcashUpload = document.getElementById('gcash-upload');
            gcashUpload.style.display = show ? 'block' : 'none';
        }

        document.getElementById('checkoutForm').addEventListener('submit', function(event) {
            const confirmation = confirm("Are you sure you want to proceed with the checkout?");
            if (!confirmation) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>

