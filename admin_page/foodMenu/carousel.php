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
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    /* Reset some basic styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Basic container styles */
.container {
    width: 80%;
    margin: 0 auto;
    padding: 20px;
    text-align: center;
}

/* Styling for the form elements */
.upload-form {
    margin-bottom: 20px;
    display: inline-block;
    text-align: left;
}

/* Label and input field styling */
.upload-form label {
    font-size: 16px;
    margin-bottom: 8px;
    display: block;
}

.upload-form input[type="file"] {
    margin-bottom: 12px;
    padding: 8px;
    width: 100%;
}

.upload-form button {
    padding: 10px 20px;
    font-size: 16px;
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

.upload-form button:hover {
    background-color: #0056b3;
}

</style>
<body>
<h2>Upload Carousel and Promotional Images</h2>

<!-- Form for Carousel Image -->
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="image_type" value="carousel">
    <label for="carousel_image">Upload Carousel Image:</label>
    <input type="file" name="image" id="carousel_image" accept="image/*" required>
    <button type="submit">Upload Carousel Image</button>
</form>

<br>

<!-- Form for Left Image -->
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="image_type" value="left">
    <label for="left_image">Upload Left Image:</label>
    <input type="file" name="image" id="left_image" accept="image/*" required>
    <button type="submit">Upload Left Image</button>
</form>

<br>

<!-- Form for Right Image -->
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="image_type" value="right">
    <label for="right_image">Upload Right Image:</label>
    <input type="file" name="image" id="right_image" accept="image/*" required>
    <button type="submit">Upload Right Image</button>
</form>
<br>
<br>
imgae view <br>
<input type="file" id="carousel_image" onchange="previewImage(event)">
<img id="carousel_image_preview" style="width: 100px; margin-top: 10px;" />

</body>
</html>


<script>
function previewImage(event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('carousel_image_preview').src = e.target.result;
    };
    reader.readAsDataURL(file);
}
</script>

