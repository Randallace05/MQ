<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Food Menu - Dashboard</title>

    <!-- Custom fonts and styles -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../dashboard/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <div id="wrapper">

        <!-- Sidebar -->
        <?php include("../includesAdmin/sidebar.php"); ?>
        
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Topbar -->
                <?php include("../includesAdmin/topbar.php"); ?>
                
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800" style="color:#AB1616 !important;">Products Management</h1>
                    </div>

                    <!-- Price Filter Dropdown -->
                    <form method="GET" action="">
                        <div class="mb-3">
                            <label for="priceFilter" class="form-label">Filter by Price:</label>
                            <select name="priceFilter" id="priceFilter" class="form-select" style="width: 200px;" onchange="this.form.submit()">
                                <option value="">Show All Prices</option>
                                <option value="below_250" <?php if (isset($_GET['priceFilter']) && $_GET['priceFilter'] == 'below_250') echo 'selected'; ?>>Below ₱250</option>
                                <option value="250_300" <?php if (isset($_GET['priceFilter']) && $_GET['priceFilter'] == '250_300') echo 'selected'; ?>>₱250 - ₱300</option>
                                <option value="above_300" <?php if (isset($_GET['priceFilter']) && $_GET['priceFilter'] == 'above_300') echo 'selected'; ?>>Above ₱300</option>
                            </select>
                        </div>
                    </form>

                    <!-- Displaying Filtered Products -->
                    <div class="row">
                        <?php
                        // Sample product list (Replace this with your database fetch logic)
                        $products = [
                            ["name" => "Chili Garlic Bagoong", "price" => 278],
                            ["name" => "Chicken Binagoongan", "price" => 278],
                            ["name" => "Plain Alamang", "price" => 218],
                            ["name" => "Bangus Belly Binagoongan", "price" => 328],
                            ["name" => "Salmon Binagoongan", "price" => 328],
                        ];

                        // Filtering logic based on selected price range
                        $filteredProducts = [];
                        if (!empty($_GET['priceFilter'])) {
                            $priceFilter = $_GET['priceFilter'];
                            foreach ($products as $product) {
                                if (
                                    ($priceFilter == 'below_250' && $product['price'] < 250) ||
                                    ($priceFilter == '250_300' && $product['price'] >= 250 && $product['price'] <= 300) ||
                                    ($priceFilter == 'above_300' && $product['price'] > 300)
                                ) {
                                    $filteredProducts[] = $product;
                                }
                            }
                        } else {
                            // If no filter is selected, show all products
                            $filteredProducts = $products;
                        }

                        // Display filtered products
                        foreach ($filteredProducts as $product) {
                            echo "<div class='col-md-4'>";
                            echo "<div class='card mb-4' style='background-color: #f8f9fa;'>";
                            echo "<div class='card-body text-center'>";
                            echo "<h5 class='card-title'>{$product['name']}</h5>";
                            echo "<p class='card-text'>₱ {$product['price']}.00</p>";
                            echo "<button class='btn btn-primary'>Edit Dish</button> ";
                            echo "<button class='btn btn-danger'>Disable</button>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                        ?>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
