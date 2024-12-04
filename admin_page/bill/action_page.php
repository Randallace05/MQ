<?php
$servername = "localhost";  // Your server host
$username = "root";         // Replace with your MySQL username
$password = "";             // Replace with your MySQL password
$dbname = "login_email_verification";  // Database name

// Create a connection to the MySQL server
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO checkout (firstname, middlename, lastname, address, city, zip_code, contact_number, payment_method)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Error preparing SQL statement: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param(
        "ssssssss",
        $first_name,
        $middle_name,
        $last_name,
        $address,
        $city,
        $zip_code,
        $contact_number,
        $payment_method
    );

    // Execute the query
    if ($stmt->execute()) {
        // Redirect to a confirmation page
        header("Location: ref.php");
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
