<?php
$servername = "localhost";  // Your server host (localhost in most cases)
$username = "root";  // Replace with your MySQL username
$password = "";  // Replace with your MySQL password
$dbname = "login_email_verification";      // Database name created above

// Create a connection to the MySQL server
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input data
    $first_name = $conn->real_escape_string($_POST['firstname']);
    $middle_name = $conn->real_escape_string($_POST['Mname']);
    $last_name = $conn->real_escape_string($_POST['lname']);
    $address = $conn->real_escape_string($_POST['address']);
    $city = $conn->real_escape_string($_POST['city']);
    $zip_code = $conn->real_escape_string($_POST['z']);
    $contact_number = $conn->real_escape_string($_POST['num']);
    $payment_method = "Gcash";  // Since payment method is pre-defined

    // Insert data into the checkout_info table
    $sql = "INSERT INTO checkout (firstname, middlename, lastname, address, city, zip_code, contact_number, payment_method)
            VALUES ('$first_name', '$middle_name', '$last_name', '$address', '$city', '$zip_code', '$contact_number', '$payment_method')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";

        header("Location: ref.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>