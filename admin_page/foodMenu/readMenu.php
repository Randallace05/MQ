<?php
include '../../conn/conn.php'; 

$sql = "SELECT * FROM products";
$stmt = $conn->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            width: 250px;
            margin: 20px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
        }
        .product-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto;
        }
        .card-body {
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <img src="uploads/<?= $product['image']; ?>" class="product-img mb-3"><br>
                        <h5 class="card-title"><?= $product['name']; ?></h5>
                        <h5 class="card-title">&#8369; <?= $product['price']; ?></h5>
                        <?php
                        $toggle_action = $product['is_disabled'] == 1 ? "Enable" : "Disable";
                        ?>
                         
                        <!-- Edit and Disable Buttons -->
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editFormModal<?= $product['id']; ?>">Edit Dish</button>
                        <a href="disableMenu.php?id=<?= $product['id']; ?>" class="btn btn-danger"><?= $toggle_action; ?></a>
                    </div> 
                </div>
            </div>

            <!-- Edit Modal for each product -->
            <div class="modal fade" id="editFormModal<?= $product['id']; ?>" tabindex="-1" aria-labelledby="editFormModalLabel<?= $product['id']; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editFormModalLabel<?= $product['id']; ?>">Edit Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Edit Form -->
                            <form action="updateMenu.php?id=<?= $product['id']; ?>" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" value="<?= $product['name']; ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="number" name="price" step="0.01" class="form-control" value="<?= $product['price']; ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" class="form-control"><?= $product['description']; ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Stock</label>
                                    <input type="number" name="stock" class="form-control" value="<?= $product['stock']; ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" name="image" class="form-control">
                                    <small>Current Image: <img src="uploads/<?= $product['image']; ?>" class="product-img"></small>
                                </div>
                                <div class="mb-3">
                                    <label for="other_images" class="form-label">Other Images</label>
                                    <input type="file" name="other_images[]" multiple class="form-control">
                                    <?php if (!empty($product['other_images'])): 
                                        $other_images = json_decode($product['other_images']);
                                        foreach ($other_images as $other_image): ?>
                                            <img src="uploads/<?= $other_image; ?>" class="product-img" width="50">
                                        <?php endforeach; 
                                    endif; ?>
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>