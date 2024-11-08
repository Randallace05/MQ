<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Orders - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../dashboard/css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .menu {
            width: 200px;
            float: left;
            margin-right: 20px;
        }

        .menu ul {
            list-style-type: none;
            padding: 0;
        }

        .menu ul li {
            margin: 10px 0;
        }

        .menu ul li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            display: block;
            padding: 10px;
            background-color: #f8f8f8;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .menu ul li a:hover {
            background-color: #e8e8e8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            vertical-align: middle;
            margin-right: 10px;
        }

        .customer-name {
            display: flex;
            align-items: center;
        }

        .total {
            font-weight: bold;
        }

        .payment-gcash {
            color: green;
            font-weight: bold;
        }

        .payment-cod {
            color: #d9534f;
            font-weight: bold;
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
                        <h1 class="h3 mb-0 text-gray-800">Order</h1>
                    </div>

                    <div class="container">
        <h2>Order List</h2>
        <table>
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Menu</th>
                    <th>Total Payment</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="customer-name">
                        <img src="eren.png" alt="Eren" class="profile-pic"> Eren Jaeger
                    </td>
                    <td>Resellers Package</td>
                    <td class="total">₱ 2,110</td>
                    <td class="payment-gcash">GCash</td>
                </tr>
                <tr>
                    <td class="customer-name">
                        <img src="reiner.png" alt="Reiner" class="profile-pic"> Reiner Braum
                    </td>
                    <td>Chili Garlic, Salmon Belly (2), Chicken Bagoong</td>
                    <td class="total">₱ 1,223</td>
                    <td class="payment-gcash">GCash</td>
                </tr>
                <tr>
                    <td class="customer-name">
                        <img src="levi.png" alt="Levi" class="profile-pic"> Levi Ackerman
                    </td>
                    <td>Chili Garlic, Salmon Belly (2), Chicken Bagoong</td>
                    <td class="total">₱ 1,234</td>
                    <td class="payment-gcash">GCash</td>
                </tr>
                <tr>
                    <td class="customer-name">
                        <img src="historia.png" alt="Historia" class="profile-pic"> Historia
                    </td>
                    <td>Salmon Belly (2)</td>
                    <td class="total">₱ 788</td>
                    <td class="payment-gcash">GCash</td>
                </tr>
                <tr>
                    <td class="customer-name">
                        <img src="armin.png" alt="Armin" class="profile-pic"> Armin Arlet
                    </td>
                    <td>Pork Binagoongan (2)</td>
                    <td class="total">₱ 678</td>
                    <td class="payment-gcash">GCash</td>
                </tr>
                <tr>
                    <td class="customer-name">
                        <img src="hanji.png" alt="Hanji" class="profile-pic"> Hanji Zoe
                    </td>
                    <td>Pork Binagoongan (2)</td>
                    <td class="total">₱ 678</td>
                    <td class="payment-cod">COD</td>
                </tr>
                <tr>
                    <td class="customer-name">
                        <img src="erwin.png" alt="Erwin" class="profile-pic"> Erwin Smith
                    </td>
                    <td>Chili Garlic, Salmon Belly (2)</td>
                    <td class="total">₱ 678</td>
                    <td class="payment-cod">COD</td>
                </tr>
                <tr>
                    <td class="customer-name">
                        <img src="ymir.png" alt="Ymir" class="profile-pic"> Ymir
                    </td>
                    <td>Chili Garlic, Salmon Belly (2)</td>
                    <td class="total">₱ 586</td>
                    <td class="payment-cod">COD</td>
                </tr>
                <tr>
                    <td class="customer-name">
                        <img src="annie.png" alt="Annie" class="profile-pic"> Annie Leonhart
                    </td>
                    <td>Chili Garlic</td>
                    <td class="total">₱ 345</td>
                    <td class="payment-cod">COD</td>
                </tr>
            </tbody>
        </table>
    </div>


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

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>