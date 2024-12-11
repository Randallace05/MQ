<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $uploadDir = 'uploadsC/'; // Change to your directory
    $uploadFile = $uploadDir . basename($_FILES['image']['name']);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
        include '../../conn/conn.php';
        $sql = "INSERT INTO carousel_images (image_path) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $uploadFile);

        if ($stmt->execute()) {
            echo "Image uploaded and saved to database.";
        } else {
            echo "Database error: " . $conn->error;
        }
    } else {
        echo "Failed to upload image.";
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <label for="image">Upload Carousel Image:</label>
    <input type="file" name="image" id="image" accept="image/*" required>
    <button type="submit">Upload</button>
</form>