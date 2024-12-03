<?php
// Database connection details
$host = "localhost"; // Update as per your database host
$username = "root"; // Update as per your database username
$password = ""; // Update as per your database password
$dbname = "login_email_verification"; // Update with your database name

// Establish a connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check for a connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query the database for low-stock or out-of-stock products
$sql = "SELECT id, name, stock FROM products WHERE stock <= 5"; // Adjust threshold if needed
$result = $conn->query($sql);

// Initialize an array to hold the data
$data = [];

// Fetch the results and populate the array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Return the data as a JSON response
header('Content-Type: application/json');
echo json_encode($data);

// Close the database connection
$conn->close();
?>
