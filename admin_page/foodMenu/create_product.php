<?php
include '../../conn/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insert the new product into the `products` table
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Handle the product image upload
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    move_uploaded_file($image_tmp, "uploads/" . $image);

    $sql = "INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $name, $price, $description, $image);
    $stmt->execute();
    $product_id = $stmt->insert_id;  // Get the ID of the newly inserted product

    // Insert each stock batch into the `product_stocks` table
    $stock_batches = $_POST['stock_batch'];
    $expiration_dates = $_POST['expiration_date_batch'];
    $code_names = $_POST['code_name_batch'];

    foreach ($stock_batches as $index => $stock) {
        $expiration_date = $expiration_dates[$index];
        $code_name = $code_names[$index];

        $sql = "INSERT INTO product_stocks (product_id, stock, expiration_date, code_name) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iiss', $product_id, $stock, $expiration_date, $code_name);
        $stmt->execute();
    }

    // Redirect after successful product creation
    header("Location: products.php");
    exit;
}
?>
