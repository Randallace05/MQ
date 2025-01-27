<?php
include("../../conn/conn.php");

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
            // Get POST data for the checkout table
            $tbl_user_id = intval($_POST['tbl_user_id']); // Ensure integer type for user ID
            $firstname = htmlspecialchars($_POST['firstname']);
            $middlename = htmlspecialchars($_POST['Mname']);
            $lastname = htmlspecialchars($_POST['lname']);
            $address = htmlspecialchars($_POST['address']);
            $city = htmlspecialchars($_POST['city']);
            $zip_code = htmlspecialchars($_POST['z']);
            $contact_number = htmlspecialchars($_POST['num']);
            $payment_method = htmlspecialchars($_POST['payment_method']);
            $grand_total = floatval($_POST['grand_total']); // Ensure float for total

            // Insert into checkout table
            $checkoutStmt = $conn->prepare("
                INSERT INTO checkout (tbl_user_id, firstname, middlename, lastname, address, city, zip_code, contact_number, payment_method, grand_total)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $checkoutStmt->bind_param(
                "issssssssd",
                $tbl_user_id,
                $firstname,
                $middlename,
                $lastname,
                $address,
                $city,
                $zip_code,
                $contact_number,
                $payment_method,
                $grand_total
            );

            if ($checkoutStmt->execute()) {
                echo "<script>
                    alert('File uploaded and checkout information saved successfully!');
                    window.location.href = '../bill/receipt.php';
                </script>";
            } else {
                echo "<script>
                    alert('Error saving checkout information to database: " . $checkoutStmt->error . "');
                    window.history.back();
                </script>";
            }

            $checkoutStmt->close();
        } else {
            echo "<script>
                alert('Error saving file info to database: " . $stmt->error . "');
                window.history.back();
            </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
            alert('Error uploading file.');
            window.history.back();
        </script>";
    }
}

$conn->close();
?>
