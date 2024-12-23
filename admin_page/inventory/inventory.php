<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "login_email_verification");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get sorting order from query parameter
$sort_order = isset($_GET['sort']) ? $_GET['sort'] : 'asc';
$order_by = ($sort_order === 'desc') ? 'DESC' : 'ASC';

// Fetch data from 'products' table with sorting
$sql = "SELECT id, name, stock, price, description FROM products ORDER BY stock $order_by";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Inventory</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../dashboard/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include("../includesAdmin/sidebar.php"); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include("../includesAdmin/topbar.php"); ?>
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h1 class="h3 text-gray-800">Inventory</h1>
                        <select id="sortDropdown" class="form-control w-auto" onchange="sortInventory()">
                            <option value="asc" <?php echo $sort_order === 'asc' ? 'selected' : ''; ?>>Low to High Stocks</option>
                            <option value="desc" <?php echo $sort_order === 'desc' ? 'selected' : ''; ?>>High to Low Stocks</option>
                        </select>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="inventoryTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Product ID</th>
                                            <th>Product Name</th>
                                            <th>Stocks</th>
                                            <th>Price</th>
                                            <th>Action Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>" . $row['id'] . "</td>"; // Display Product ID
                                                echo "<td>" . $row['name'] . "</td>";
                                                echo "<td>" . $row['stock'] . "</td>";
                                                echo "<td>" . $row['price'] . "</td>";
                                                echo "<td>
                                                        <button class='btn btn-primary btn-sm' onclick='addStock(" . $row['id'] . ")'>Add Stock</button>
                                                    </td>";
                                                echo "<td>
                                                        <button class='btn btn-primary btn-sm'>Edit</button> 
                                                        <button class='btn btn-danger btn-sm'>Delete</button>
                                                    </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6'>No products found.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2024</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script>
        // JavaScript function to handle sorting
        function sortInventory() {
            const sortOrder = document.getElementById('sortDropdown').value;
            window.location.href = '?sort=' + sortOrder;
        }

        // JavaScript function to handle "Add Stock" button click
        function addStock(productId) {
            alert('Add stock functionality for Product ID: ' + productId);
            // Implement your add stock logic here, such as opening a modal or making an AJAX call
        }
    </script>
</body>

</html>

<?php
$conn->close();
?>
