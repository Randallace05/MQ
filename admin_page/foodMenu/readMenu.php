<?php
include '../../conn/conn.php';

// Fetch all products for mapping product names to IDs
$sql = "SELECT * FROM products";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

// Create a product map for easy lookup by name
$product_map = [];
foreach ($products as $product) {
    $product_map[$product['name']] = $product;
}

// Fetch the transaction history data
$sql = "SELECT * FROM transaction_history";
$result = $conn->query($sql);

$product_details = [];
while ($row = $result->fetch_assoc()) {
    // Split cart_items into individual items
    $cart_items = explode(',', $row['cart_items']); // Assuming items are comma-separated

    foreach ($cart_items as $item) {
        // Extract product name and quantity using regex
        if (preg_match('/^(.*?) $$(\d+)x$$$/', trim($item), $matches)) {
            $product_name = $matches[1];
            $quantity = (int) $matches[2];

            // Check if product exists in the product map
            if (isset($product_map[$product_name])) {
                $product = $product_map[$product_name];
                $product_details[] = [
                    'product_id' => $product['id'],
                    'product_name' => $product_name,
                    'price' => $product['price'],
                    'quantity' => $quantity,
                ];
            }
        }
    }
}

// Aggregate quantities and calculate total price for each product
$aggregated_details = [];
foreach ($product_details as $detail) {
    $product_id = $detail['product_id'];
    if (!isset($aggregated_details[$product_id])) {
        $aggregated_details[$product_id] = [
            'product_id' => $detail['product_id'],
            'product_name' => $detail['product_name'],
            'quantity' => $detail['quantity'],
            'total_price' => $detail['price'] * $detail['quantity'],
        ];
    } else {
        $aggregated_details[$product_id]['quantity'] += $detail['quantity'];
        $aggregated_details[$product_id]['total_price'] += $detail['price'] * $detail['quantity'];
    }
}

// Function to get the next batch number
function getNextBatchNumber($conn, $product_id) {
    $sql = "SELECT MAX(CAST(SUBSTRING(condname, -1) AS UNSIGNED)) as max_batch 
            FROM products 
            WHERE id = ? AND condname REGEXP '^[A-Z]+[0-9]+$'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return ($row['max_batch'] !== null) ? $row['max_batch'] + 1 : 2;
}

// Handle form submission for adding a new batch
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_batch'])) {
    $product_id = $_POST['product_id'];
    $new_stock = $_POST['new_stock'];
    $new_expiration_date = $_POST['new_expiration_date'];

    // Fetch the existing product details
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        $next_batch_number = getNextBatchNumber($conn, $product_id);
        $new_condname = $product['condname'] . $next_batch_number;

        // Insert new batch
        $insert_sql = "INSERT INTO products (name, price, image, description, other_images, stock, expiration_date, condname) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("sdssssss", 
            $product['name'], 
            $product['price'], 
            $product['image'], 
            $product['description'], 
            $product['other_images'], 
            $new_stock, 
            $new_expiration_date, 
            $new_condname
        );
        
        if ($insert_stmt->execute()) {
            echo "<script>alert('New batch added successfully!');</script>";
        } else {
            echo "<script>alert('Error adding new batch: " . $insert_stmt->error . "');</script>";
        }
        $insert_stmt->close();
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
< lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    /* Base Styles */
    
/* General Body Styling */
body {
    font-family: 'Inter', sans-serif;
    background-color: #f4f5f7;
    color: #2e2e2e;
    margin: 0;
    padding: 0;
}

/* Headers and Titles */
h2 {
    color: #34495e;
    font-weight: 700;
    text-align: center;
    margin-bottom: 20px;
}

/* Product Cards */
.card {
    border-radius: 12px;
    box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.1);
    background: linear-gradient(135deg, #ffffff, #f9f9f9);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.15);
}

.product-img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    margin: 15px auto;
    display: block;
}

.card-body {
    text-align: center;
    padding: 20px;
}

.card-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 10px;
    color: #34495e;
}

.card-price {
    font-size: 1.1rem;
    color: #7f8c8d;
    margin-bottom: 15px;
}
.table-container {
    margin-bottom: 20px;
}

h2 {
    color: #EA7C69;
    font-weight: bold;
}


.btn-container {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.btn-primary {
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: bold;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #2575fc, #6a11cb);
    box-shadow: 0px 4px 10px rgba(106, 17, 203, 0.4);
}

.btn-danger {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: bold;
    transition: all 0.3s ease;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #c0392b, #e74c3c);
    box-shadow: 0px 4px 10px rgba(231, 76, 60, 0.4);
}

/* Responsive Tables */
.table-container {
    margin-top: 30px;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin: 0 auto;
    background-color: white;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

.table thead th {
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    color: white;
    text-transform: uppercase;
    font-size: 0.9rem;
    padding: 15px;
}

.table tbody tr {
    transition: background-color 0.3s ease;
}

.table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

.table tbody tr:hover {
    background-color: #f1f1f1;
}

.table tbody td {
    padding: 15px;
    text-align: center;
}

/* Small product image in the table */
.product-img-small {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

/* Sort Buttons */
.sort-buttons {
    display: flex;
    justify-content: right;
}

.sort-buttons button {
    margin: 0 5px;
    padding: 8px 15px;
    border-radius: 8px;
    font-weight: 200;
    border: none;
    color: #ffffff;
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    transition: all 0.3s ease;
}

.sort-buttons button:hover {
    background: linear-gradient(135deg, #2575fc, #6a11cb);
    box-shadow: 0px 4px 10px rgba(106, 17, 203, 0.4);
    text-align: left;
}

/* Modal Styles */
.modal-content {
    border-radius: 12px;
    box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
    font-size: 16px;
}

.modal-header {
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    color: white;
    border-bottom: none;
}

.modal-title {
    font-weight: 600;
}

.btn-close {
    color: white;
}

/* Footer */
footer {
    text-align: center;
    padding: 20px;
    margin-top: 50px;
    background-color: #2c3e50;
    color: #ecf0f1;
    font-size: 0.9rem;
}

footer a {
    color: #6a11cb;
    text-decoration: none;
    font-weight: 600;
}

footer a:hover {
    text-decoration: underline;
}
.table-container {
    margin-bottom: 20px;
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.1);
}

.table th,
.table td {
    vertical-align: middle; /* Ensures content aligns vertically in cells */
}

.modal-header {
    border-bottom: none;
}

.modal-content {
    border-radius: 8px;
}

.table td:first-child {
    font-weight: normal;
    width: 150px;
}

.btn-close-white {
    filter: brightness(0) invert(1);
}

#enableDisableBtn:hover, #addBatchBtn:hover {
    background-color: #5710A7 !important;
}
</style>


</head>
<>

<div class="container mt-5 px-4"> <!-- Added px-4 for left/right indent -->
    <div class="row justify-content-center g-4"> <!-- Added g-4 for consistent gap -->
        <?php foreach ($products as $product): ?>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body  flex-column">
                        <img src="uploads/<?= $product['image']; ?>" class="product-img mb-3">
                        <h5 class="card-title"><?= $product['name']; ?></h5>
                        <h5 class="card-title">&#8369; <?= $product['price']; ?></h5>
                        <?php
                        $toggle_action = $product['is_disabled'] == 1 ? "Enable" : "Disable";
                        ?>

                        <!-- Updated button layout -->
                        <div class="mt-auto d-flex flex-column gap-2"> <!-- Added flex-column and gap-2 -->
                            <button class="btn btn-primary w-100" data-bs-toggle="modal"
                                    data-bs-target="#editFormModal<?= $product['id']; ?>">
                                Edit Dish
                            </button>
                            <a href="disableMenu.php?id=<?= $product['id']; ?>"
                               class="btn btn-danger w-100">
                                <?= $toggle_action; ?>
                            </a>
                        </div>
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
                                    <label for="expiration_date" class="form-label">Expiration Date</label>
                                    <input type="date" name="expiration_date" class="form-control" value="<?= $product['expiration_date']; ?>">
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
    <div class="row align-items-start g-4"> <!-- Use align-items-start for proper vertical alignment -->
        <div class="col-md-6">
            <div class="table-container">
                <h2 class="text-center mb-4" style="color: #EA7C69;">Product Inventory</h2>
                <div class="table-responsive">
                    <!-- Product Inventory Table -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Image</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td>
                                        <a href="#" class="text-decoration-none" data-bs-toggle="modal"
                                        data-bs-target="#productDetailsModal" 
                                        data-product-id="<?= htmlspecialchars($product['id']); ?>">
                                            <?= htmlspecialchars($product['name']); ?>
                                        </a>
                                    </td>
                                    <td>&#8369; <?= number_format($product['price'], 2); ?></td>
                                    <td>
                                        <?php if (!empty($product['image'])): ?>
                                            <img src="uploads/<?= htmlspecialchars($product['image']); ?>" class="product-img-small" alt="Product Image">
                                        <?php else: ?>
                                            <span>No image</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order History Section -->
        <div class="col-md-6">
            <div class="table-container">
                <h2 class="text-center mb-4" style="color: #EA7C69;">Order History</h2>

                     <!-- Order History Table -->
                <div class="table-responsive">
                    <table class="table table-bordered" id="orderHistoryTable">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Product Name</th>
                                <th>Quantity Ordered</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($aggregated_details as $detail): ?>
                                <tr>
                                    <td><?= $detail['product_id']; ?></td>
                                    <td><?= htmlspecialchars($detail['product_name']); ?></td>
                                    <td class="quantity"><?= $detail['quantity']; ?></td>
                                    <td>&#8369; <?= number_format($detail['total_price'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Details Modal -->
<div class="modal fade" id="productDetailsModal" tabindex="-1" aria-labelledby="productDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #6A11CB; color: white;">
                <h5 class="modal-title" id="productDetailsModalLabel">Product Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tr>
                        <th>ID</th>
                        <td id="productId"></td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td id="productName"></td>
                    </tr>
                    <tr>
                        <th>Codename</th>
                        <td id="productCodename"></td>
                    </tr>
                    <tr>
                        <th>Total Stock</th>
                        <td id="productTotalStock"></td>
                    </tr>
                </table>
                <h6>Batch Information:</h6>
                <table class="table" id="batchTable">
                    <thead>
                        <tr>
                            <th>Batch Number</th>
                            <th>Stock</th>
                            <th>Expiration Date</th>
                        </tr>
                    </thead>
                    <tbody id="batchInfo">
                        <!-- Batch info will be populated here -->
                    </tbody>
                </table>
                <button id="enableDisableBtn" class="btn" style="background-color: #6A11CB; color: white;">Enable/Disable</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn" style="background-color: #6A11CB; color: white;" id="addBatchBtn">Add Batch</button>
            </div>
        </div>
    </div>
</div>
    
<!-- Add Batch Modal -->
<div class="modal fade" id="addBatchModal" tabindex="-1" aria-labelledby="addBatchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBatchModalLabel">Add New Batch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addBatchForm" action="foodMenuBackend.php" method="post">
                    <input type="hidden" id="batchProductId" name="product_id">
                    <input type="hidden" name="add_batch" value="1">
                    <div class="mb-3">
                        <label for="batchStock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="batchStock" name="stock" required>
                    </div>
                    <div class="mb-3">
                        <label for="batchExpirationDate" class="form-label">Expiration Date</label>
                        <input type="date" class="form-control" id="batchExpirationDate" name="expiration_date" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Batch</button>
                </form>
            </div>
        </div>
    </div>
</div>

    


<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<script>
    const sortHighButton = document.getElementById('sortHigh');
    const sortLowButton = document.getElementById('sortLow');
    const tableBody = document.querySelector('#orderHistoryTable tbody');

    // Function to sort table rows
    function sortTable(order) {
        const rows = Array.from(tableBody.querySelectorAll('tr'));

        // Sort rows based on quantity
        rows.sort((a, b) => {
            const quantityA = parseInt(a.querySelector('.quantity').textContent);
            const quantityB = parseInt(b.querySelector('.quantity').textContent);
            return order === 'high' ? quantityB - quantityA : quantityA - quantityB;
        });

        // Re-append sorted rows to the table body
        rows.forEach(row => tableBody.appendChild(row));
    }

    // Event listeners for sorting buttons
    sortHighButton.addEventListener('click', () => sortTable('high'));
    sortLowButton.addEventListener('click', () => sortTable('low'));
</script>
<script>
// Product Details Modal
const productDetailsModal = document.getElementById('productDetailsModal');
productDetailsModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const productId = button.getAttribute('data-product-id');
    
    fetch(`getProductDetails.php?id=${productId}`)
        .then(response => response.json())
        .then(data => {
            if (!data.error) {
                document.getElementById('productId').textContent = data.id;
                document.getElementById('productName').textContent = data.name;
                document.getElementById('productCodename').textContent = data.codename || '';
                document.getElementById('productTotalStock').textContent = data.total_stock;

                const batchInfo = document.getElementById('batchInfo');
                batchInfo.innerHTML = '';
                if (data.batch_info) {
                    const batches = data.batch_info.split('|');
                    batches.forEach(batch => {
                        const [batchNumber, stock, expirationDate] = batch.split(':');
                        const row = `
                            <tr>
                                <td>${batchNumber}</td>
                                <td>${stock}</td>
                                <td>${expirationDate}</td>
                            </tr>
                        `;
                        batchInfo.innerHTML += row;
                    });
                }

                const enableDisableBtn = document.getElementById('enableDisableBtn');
                enableDisableBtn.textContent = data.is_disabled == 1 ? 'Enable' : 'Disable';
                enableDisableBtn.setAttribute('data-product-id', data.id);
                enableDisableBtn.setAttribute('data-is-disabled', data.is_disabled);

                const addBatchBtn = document.getElementById('addBatchBtn');
                addBatchBtn.onclick = function() {
                    const addBatchModal = new bootstrap.Modal(document.getElementById('addBatchModal'));
                    document.getElementById('batchProductId').value = data.id;
                    addBatchModal.show();
                };
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Error loading product details: ' + error.message);
        });
});
</script>
</body>
</html>





