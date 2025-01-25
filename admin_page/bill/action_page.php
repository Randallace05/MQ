<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_email_verification";

// Create a connection to the MySQL server
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in
if (!isset($_SESSION['unique_id'])) {
    die("User ID is not set in the session. Please log in.");
}

$tbl_user_id = intval($_SESSION['tbl_user_id']); // Ensure ID is an integer for safety

// Check if the user exists in the tbl_user table
$user_check_stmt = $conn->prepare("SELECT * FROM tbl_user WHERE tbl_user_id = ?");
$user_check_stmt->bind_param("i", $tbl_user_id);
$user_check_stmt->execute();
$user_result = $user_check_stmt->get_result();

if ($user_result->num_rows === 0) {
    die("Invalid user ID. Please log in again.");
}

$cartItems = [];
$total_amount = 0; // Initialize total amount

// Fetch selected cart items
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['selected_products']) && !empty($_POST['selected_products'])) {
    $selected_products = array_map('intval', $_POST['selected_products']); // Sanitize input

    if (count($selected_products) > 0) {
        // Prepare query with placeholders
        $placeholders = implode(',', array_fill(0, count($selected_products), '?'));
        $stmt = $conn->prepare("SELECT * FROM cart WHERE tbl_user_id = ? AND cart_id IN ($placeholders)");

        // Bind dynamic parameters
        $types = str_repeat('i', count($selected_products) + 1);
        $stmt->bind_param($types, $tbl_user_id, ...$selected_products);

        $stmt->execute();
        $result = $stmt->get_result();
        $cartItems = $result->fetch_all(MYSQLI_ASSOC);

        // Calculate total amount
        foreach ($cartItems as $cartItem) {
            $total_amount += $cartItem['price'] * $cartItem['quantity'];
        }
    } else {
        die("No valid products selected.");
    }
} else {
    die("No items selected. Please go back and select items to proceed.");
}

// Process the form submission
if (isset($_POST['firstname'])) {
    // Retrieve and sanitize input data
    $first_name = trim($_POST['firstname']);
    $middle_name = trim($_POST['Mname']);
    $last_name = trim($_POST['lname']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $zip_code = trim($_POST['z']);
    $contact_number = trim($_POST['num']);

    // Validate required fields
    if (empty($first_name) || empty($middle_name) || empty($last_name) || empty($address) ||
        empty($city) || empty($zip_code) || empty($contact_number)) {
        die("All fields are required. Please go back and fill out the form.");
    }

    // Validate and retrieve the payment method
    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : null;
    if (!in_array($payment_method, ['Cash on Delivery', 'Gcash Payment'])) {
        die("Invalid payment method selected. Please go back and try again.");
    }

    // Handle GCash payment proof upload
    $gcash_proof_path = null;
    if ($payment_method === "Gcash Payment" && isset($_FILES['gcash_proof']) && $_FILES['gcash_proof']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../uploads/payment_proofs/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = uniqid() . '_' . basename($_FILES['gcash_proof']['name']);
        $upload_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['gcash_proof']['tmp_name'], $upload_file)) {
            $gcash_proof_path = $file_name;
        } else {
            die("Error uploading GCash payment proof. Please try again.");
        }
    }

    // Insert into orders table
    $order_stmt = $conn->prepare(
        "INSERT INTO orders (tbl_user_id, order_date, total_amount, shipping_address, payment_method)
        VALUES (?, NOW(), ?, ?, ?)"
    );

    $order_stmt->bind_param("idss", $tbl_user_id, $total_amount, $address, $payment_method);
    if (!$order_stmt->execute()) {
        die("Error inserting into orders table: " . $order_stmt->error);
    }

    $orders_id = $order_stmt->insert_id;
    $order_stmt->close();

    // Insert data into the checkout table
    $cart_items_combined = [];
    foreach ($cartItems as $cartItem) {
        $cart_items_combined[] = "{$cartItem['name']} ({$cartItem['quantity']}x)";
    }
    $cart_items_string = implode(", ", $cart_items_combined);

    $checkout_stmt = $conn->prepare(
        "INSERT INTO checkout (orders_id, tbl_user_id, cart_items, firstname, middlename, lastname, address, city, zip_code, contact_number, payment_method, gcash_proof)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $checkout_stmt->bind_param(
        "iissssssssss",
        $orders_id,
        $tbl_user_id,
        $cart_items_string,
        $first_name,
        $middle_name,
        $last_name,
        $address,
        $city,
        $zip_code,
        $contact_number,
        $payment_method,
        $gcash_proof_path
    );

    if (!$checkout_stmt->execute()) {
        die("Error inserting into checkout table: " . $checkout_stmt->error);
    }

    $checkout_stmt->close();

 // Delete selected items from cart
 if (count($selected_products) > 0) {
    $placeholders = implode(',', array_fill(0, count($selected_products), '?'));
    $stmt = $conn->prepare("DELETE FROM cart WHERE tbl_user_id = ? AND cart_id IN ($placeholders)");
    $types = str_repeat('i', count($selected_products) + 1);
    $stmt->bind_param($types, $tbl_user_id, ...$selected_products);
    $stmt->execute();
}

    // Redirect to receipt
    header("Location: receipt.php?order_id=$orders_id");
    exit;
}

// Close the database connection
$conn->close();
?>
