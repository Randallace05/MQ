<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Users - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../dashboard/css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .table-container {
            width: 60%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .table-header, .table-row {
            display: flex;
            justify-content: space-between;
            background-color: #f5e6e7;
            text-align: left;
            color: #e84949;
            padding: 10px;
        }
        .table-row {
            background-color: #f9eaea;
        }
        .table-row:nth-child(even) {
            background-color: #f7dada;
        }
        .table-cell {
            width: 48%;
        }
        .avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #d3d3d3;
            margin-right: 10px;
            display: inline-block;
        }
        /* Media Query for Smaller Screens */
        @media (max-width: 768px) {
            .table-container {
                width: 100%;
            }
            .table-header, .table-row {
                flex-direction: column;
                align-items: flex-start;
            }
            .table-cell {
                width: 100%;
                padding: 5px 0;
            }
        }
        @media (max-width: 480px) {
            .table-header, .table-row {
                padding: 8px;
            }
            .table-cell {
                font-size: 14px;
            }
            .avatar {
                width: 20px;
                height: 20px;
            }
        }
    </style>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include("../includesAdmin/sidebar.php"); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <?php include("../includesAdmin/topbar.php"); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Accounts</h1>
                    </div>

                    <!-- Dropdown for Filtering -->
                    <form method="GET" action="">
                        <label for="userRoleFilter">Filter by Role:</label>
                        <select name="user_role" id="userRoleFilter" onchange="this.form.submit()">
                            <option value="all" <?php echo isset($_GET['user_role']) && $_GET['user_role'] == 'all' ? 'selected' : ''; ?>>All</option>
                            <option value="customer" <?php echo isset($_GET['user_role']) && $_GET['user_role'] == 'customer' ? 'selected' : ''; ?>>Customer</option>
                            <option value="distributor" <?php echo isset($_GET['user_role']) && $_GET['user_role'] == 'distributor' ? 'selected' : ''; ?>>Distributor</option>
                            <!-- Add more options as needed -->
                        </select>
                    </form>

                    <?php
                    include '../../conn/conn.php';

                    // Get selected user role
                    $selectedRole = isset($_GET['user_role']) ? $_GET['user_role'] : 'all';

                    // Adjust SQL query based on selected role
                    if ($selectedRole == 'all') {
                        $query = "SELECT first_name, last_name, user_role FROM tbl_user WHERE user_role != 'admin'";
                    } else {
                        $query = "SELECT first_name, last_name, user_role FROM tbl_user WHERE user_role = :user_role AND user_role != 'admin'";
                    }

                    $stmt = $conn->prepare($query);

                    // Bind the parameter if a specific role is selected
                    if ($selectedRole != 'all') {
                        $stmt->bindParam(':user_role', $selectedRole, PDO::PARAM_STR);
                    }

                    $stmt->execute();
                    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>

                    <!-- Table Container -->
                    <div class="table-container">
                        <div class="table-header">
                            <span class="table-cell">Name</span>
                            <span class="table-cell">Type</span>
                        </div>

                        <?php foreach($users as $row) { ?>
                            <div class="table-row">
                                <div class="table-cell">
                                    <div class="avatar"></div> 
                                    <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
                                </div>
                                <div class="table-cell">
                                    <?php echo htmlspecialchars($row['user_role']); ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>
