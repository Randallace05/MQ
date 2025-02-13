<?php
function uploadImage($imageType, $uploadDir = 'uploadsC/') {
    if (!isset($_FILES['image'])) {
        echo "No image file uploaded.";
        return;
    }

    // Create upload directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $uploadFile = $uploadDir . basename($_FILES['image']['name']);
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
        echo "Failed to upload image.";
        return;
    }

    include '../../conn/conn.php';

    // Determine the appropriate SQL query based on image type
    switch ($imageType) {
        case 'left':
            $sql = "UPDATE carousel_images SET left_image_path = ? WHERE id = 1";
            break;
        case 'right':
            $sql = "UPDATE carousel_images SET right_image_path = ? WHERE id = 1";
            break;
        case 'carousel':
            $sql = "INSERT INTO carousel_images (image_path) VALUES (?)";
            break;
        default:
            echo "Invalid image type.";
            return;
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $uploadFile);

    if ($stmt->execute()) {
        echo ucfirst($imageType) . " image uploaded and updated successfully.";
    } else {
        echo "Database error: " . $conn->error;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['image_type'])) {
    uploadImage($_POST['image_type']);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Carousel and Promotional Images</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../dashboard/css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Add this in the <head> section of your HTML -->
</head>
<style>
    /* Reset and base styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        color: #333;
        background-color: #f4f4f4;
        padding: 20px;
    }

    /* Container styles */
    .container {
        max-width: 800px;
        margin: 0 auto;
        background-color: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #2c3e50;
    }

    /* Form styles */
    form {
        margin-bottom: 30px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #34495e;
    }

    input[type="file"] {
        display: block;
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    button {
        display: inline-block;
        background-color: #3498db;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #2980b9;
    }

    /* Image preview */
    .image-preview {
        margin-top: 20px;
        text-align: center;
    }

    .image-preview img {
        max-width: 300px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }
</style>
<body>
    <div class="container">
        <div class="btn btn-circle">
            <a href="foodMenu.php" class="btn btn-primary">Back</a>
        </div>

        <h2>Upload Carousel and Promotional Images</h2>

        <!-- Form for Carousel Image -->
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="image_type" value="carousel">
            <label for="carousel_image">Upload Carousel Image:</label>
            <input type="file" name="image" id="carousel_image" accept="image/*" required onchange="previewImage(event, 'carousel_image_preview')">
            <button type="submit">Upload Carousel Image</button>
        </form>

        <!-- Form for Left Image -->
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="image_type" value="left">
            <label for="left_image">Upload Left Image:</label>
            <input type="file" name="image" id="left_image" accept="image/*" required onchange="previewImage(event, 'left_image_preview')">
            <button type="submit">Upload Left Image</button>
        </form>

        <!-- Form for Right Image -->
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="image_type" value="right">
            <label for="right_image">Upload Right Image:</label>
            <input type="file" name="image" id="right_image" accept="image/*" required onchange="previewImage(event, 'right_image_preview')">
            <button type="submit">Upload Right Image</button>
        </form>

        <!-- Image Previews -->
        <div class="image-preview">
            <h3>Image Previews</h3>
            <img id="carousel_image_preview" alt="Carousel Image Preview" />
            <img id="left_image_preview" alt="Left Image Preview" />
            <img id="right_image_preview" alt="Right Image Preview" />
        </div>
    </div>

    <script>
        function previewImage(event, previewId) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    </script>
</body>
</html>

