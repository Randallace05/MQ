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
    <style>
        /* Global Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Body Styling */
        body {
            background-color: #f9f9f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* Container Styling */
        .container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            padding: 20px;
        }

        /* Header Styling */
        .container h2 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Form Styling */
        .upload-form {
            margin-bottom: 20px;
        }

        .upload-form label {
            font-size: 16px;
            margin-bottom: 10px;
            display: block;
        }

        .upload-form input[type="file"] {
            margin-bottom: 10px;
            padding: 8px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .upload-form button {
            padding: 12px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        .upload-form button:hover {
            background-color: #0056b3;
        }

        /* Preview Section Styling */
        .preview {
            margin-top: 15px;
            text-align: center;
        }

        .preview img {
            width: 100px;
            height: auto;
            border-radius: 5px;
            margin-top: 10px;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Upload Carousel and Promotional Images</h2>

        <!-- Form for Carousel Image -->
        <form class="upload-form" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="image_type" value="carousel">
            <label for="carousel_image">Upload Carousel Image:</label>
            <input type="file" name="image" id="carousel_image" accept="image/*" onchange="previewImage(event, 'carousel_preview')" required>
            <button type="submit">Upload Carousel Image</button>
            <div class="preview">
                <img id="carousel_preview" alt="Carousel Image Preview" />
            </div>
        </form>

        <!-- Form for Left Image -->
        <form class="upload-form" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="image_type" value="left">
            <label for="left_image">Upload Left Image:</label>
            <input type="file" name="image" id="left_image" accept="image/*" onchange="previewImage(event, 'left_preview')" required>
            <button type="submit">Upload Left Image</button>
            <div class="preview">
                <img id="left_preview" alt="Left Image Preview" />
            </div>
        </form>

        <!-- Form for Right Image -->
        <form class="upload-form" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="image_type" value="right">
            <label for="right_image">Upload Right Image:</label>
            <input type="file" name="image" id="right_image" accept="image/*" onchange="previewImage(event, 'right_preview')" required>
            <button type="submit">Upload Right Image</button>
            <div class="preview">
                <img id="right_preview" alt="Right Image Preview" />
            </div>
        </form>
         <a href="../foodMenu/foodMenu.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
    </div>

    <script>
        // Preview image function
        function previewImage(event, previewId) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
