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
if (!isset($_SESSION['tbl_user_id'])) {
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

// Fetch cart items for the user
$stmt = $conn->prepare("SELECT * FROM cart WHERE tbl_user_id = ?");
$stmt->bind_param("i", $tbl_user_id);
$stmt->execute();
$result = $stmt->get_result();
$cartItems = $result->fetch_all(MYSQLI_ASSOC);

// Check if there are cart items
if (empty($cartItems)) {
    die("No cart items found for this user.");
}

// Get the first cart_id (or modify logic as needed)
$cart_id = $cartItems[0]['cart_id'];

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
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
    if (isset($_POST['payment_method']) && in_array($_POST['payment_method'], ['Cash on Delivery', 'Gcash Payment'])) {
        $payment_method = $_POST['payment_method'];
    } else {
        die("Invalid payment method selected. Please go back and try again.");
    }

    // Handle GCash payment proof upload
    $gcash_proof_path = null;
    if ($payment_method === "Gcash Payment" && isset($_FILES['gcash_proof']) && $_FILES['gcash_proof']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../uploads/payment_proofs/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Create directory if it doesn't exist
        }

        $file_name = uniqid() . '_' . basename($_FILES['gcash_proof']['name']);
        $upload_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['gcash_proof']['tmp_name'], $upload_file)) {
            $gcash_proof_path = $file_name;
        } else {
            die("Error uploading GCash payment proof. Please try again.");
        }
    }

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO checkout (cart, tbl_user_id, firstname, middlename, lastname, address, city, zip_code, contact_number, payment_method, gcash_proof) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Error preparing SQL statement: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param(
        "iisssssssss",
        $cart_id,
        $tbl_user_id,
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

    // Execute the query
    if ($stmt->execute()) {
        // Redirect based on payment method
        $order_id = $conn->insert_id;
        if ($payment_method === "Cash on Delivery") {
            header("Location: receipt.php?order_id=$order_id");
        } elseif ($payment_method === "Gcash Payment") {
            header("Location: receipt.php?order_id=$order_id");
        }
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
