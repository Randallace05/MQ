<?php
include '../../conn/conn.php';

// Function to generate codename
function generateCodename($name) {
    $words = explode(' ', $name);
    $codename = '';
    foreach ($words as $word) {
        $codename .= strtoupper(substr($word, 0, 1));
    }
    return $codename;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];

    // Generate codename
    $codename = generateCodename($name);

    // Handle expiration date correctly
    $expiration_date = isset($_POST['expiration_date']) && !empty($_POST['expiration_date']) ? $_POST['expiration_date'] : null;

    // Check if expiration_date is set and not empty
    if ($expiration_date) {
        // Ensure the date is in the correct format (YYYY-MM-DD)
        $formatted_date = date('Y-m-d', strtotime($expiration_date));
        
        // Validate the formatted date
        if ($formatted_date && $formatted_date != '1970-01-01') {
            $expiration_date = $formatted_date;
        } else {
            $expiration_date = null;  // Invalid date, set to null
        }
    } else {
        $expiration_date = null;  // If no expiration date, set it to null
    }

    // Upload main image
    $image = $_FILES['image']['name'];
    if ($image) {
        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $image);
    }

    // Upload other images (up to 4)
    $other_images = [];
    if (!empty($_FILES['other_images']['tmp_name'][0])) {
        foreach ($_FILES['other_images']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['other_images']['name'][$key];
            if ($file_name) {
                move_uploaded_file($tmp_name, 'uploads/' . $file_name);
                $other_images[] = $file_name;
            }
        }
    }
    $other_images_json = json_encode($other_images);

    // SQL query with MySQLi
    $sql = "INSERT INTO products (name, price, image, description, other_images, stock, expiration_date, codename)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and bind the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sdsssdss', $name, $price, $image, $description, $other_images_json, $stock, $expiration_date, $codename);
    if ($stmt->execute()) {
        echo "Product created successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Modal Example</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            background-color: #F5E6E9!important; /* Override the background color */
        }
        .card-icon {
            font-size: 3rem;
            cursor: pointer;
            color: #007bff;
            margin-top: 80px;
        }
        .fa-color {
            color: #EA7C69;
        }
        .card-title {
            font-family: "Barlow";
            color: #EA7C69 !important;
        }
    </style>
</head>
<body>

<div class="container mt-5 px-4">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <div class="card-icon" data-bs-toggle="modal" data-bs-target="#formModal">
                        <i class="fas fa-plus-circle fa-color"></i>
                    </div>
                    <h5 class="card-title mt-3">Add New Product</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModalLabel">Create New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form goes here -->
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" name="price" step="0.01" class="form-control" id="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Main Image</label>
                        <input type="file" name="image" class="form-control" id="image" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" name="stock" class="form-control" id="stock" required>
                    </div>
                    <div class="mb-3">
                        <label for="expiration_date" class="form-label">Expiration Date</label>
                        <input type="date" name="expiration_date" class="form-control" id="expiration_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="other_images" class="form-label">Other Images</label>
                        <input type="file" name="other_images[]" multiple class="form-control" id="other_images">
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies (Popper.js and jQuery) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>

</body>
</html>

