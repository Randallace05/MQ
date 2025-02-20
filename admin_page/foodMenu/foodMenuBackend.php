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
    if (isset($_POST['add_batch'])) {
        $product_id = $_POST['product_id'];
        $new_stock = $_POST['stock'];
        $new_expiration_date = $_POST['expiration_date'];

        // Fetch the product name from the products table
        $product_query = "SELECT name FROM products WHERE id = ?";
        $product_stmt = $conn->prepare($product_query);
        $product_stmt->bind_param("i", $product_id);
        $product_stmt->execute();
        $product_result = $product_stmt->get_result();
        $product_row = $product_result->fetch_assoc();

        if (!$product_row) {
            echo "Error: Product not found.";
            exit();
        }

        $name = $product_row['name']; // Product name
        $product_stmt->close();

        // Get the next batch number
        $batch_query = "SELECT MAX(batch_number) as max_batch FROM product_batches WHERE product_id = ?";
        $batch_stmt = $conn->prepare($batch_query);
        $batch_stmt->bind_param("i", $product_id);
        $batch_stmt->execute();
        $batch_result = $batch_stmt->get_result();
        $batch_row = $batch_result->fetch_assoc();
        $next_batch_number = ($batch_row['max_batch'] !== null) ? $batch_row['max_batch'] + 1 : 1;
        
        // Generate batch codename
        $batch_codename = generateCodename($name) . '-' . $new_expiration_date . '-' . $next_batch_number;

        $batch_stmt->close();

        // Insert new batch
        $insert_sql = "INSERT INTO product_batches (product_id, stock, expiration_date, batch_number, batch_codename) VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iisis", $product_id, $new_stock, $new_expiration_date, $next_batch_number, $batch_codename);

        if ($insert_stmt->execute()) {
            header('Location: foodMenu.php');
        } else {
            echo "Error adding new batch: " . $insert_stmt->error;
        }
        $insert_stmt->close();
    } else {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];

        // Generate codename
        $codename = generateCodename($name);

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
        $sql = "INSERT INTO products (name, price, image, description, other_images, codename)
                VALUES (?, ?, ?, ?, ?, ?)";

        // Prepare and bind the statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sdssss', $name, $price, $image, $description, $other_images_json, $codename);
        if ($stmt->execute()) {
            echo "Product created successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
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

