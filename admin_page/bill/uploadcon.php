<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_email_verification";  // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $uploadDir = 'uploads/';
    
    // Check if uploads directory exists, create if not
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Move uploaded file to server
    $filePath = $uploadDir . basename($file['name']);
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        // Insert file info into database
        $stmt = $conn->prepare("INSERT INTO uploads (file_name, file_path) VALUES (?, ?)");
        $stmt->bind_param("ss", $file['name'], $filePath);

        if ($stmt->execute()) {
            echo "<script>
                alert('File uploaded and saved in database successfully!');
                window.location.href = '../bill/reciept.php';
            </script>";
        } else {
            echo "<script>
                alert('Error saving file info to database: " . $stmt->error . "');
                window.history.back();
            </script>";
        }
        

        $stmt->close();
    } else {
        echo "Error uploading file.";
    }
}

$conn->close();
?>
