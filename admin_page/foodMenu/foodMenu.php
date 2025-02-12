

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Food Menu - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../dashboard/css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Add this in the <head> section of your HTML -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
    .custom-alert {
        position: fixed;
        top: 20px;
        left: 1000px;
        width: 20%; /* Set the width to 30% */
        z-index: 4; /* Ensure it's on top of other content */
        box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Optional: Add shadow */
    }

        #progress-bar {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background-color: #ff4d4d; /* Line color (can be adjusted) */
        animation: shrinkLine 1s linear forwards; /* 1-second animation */
    }

    @keyframes shrinkLine {
        0% {
            width: 100%;
        }
        100% {
            width: 0;
        }
    }
</style>

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
                        <h1 class="h3 mb-0 text-gray-800" style="color:#AB1616 !important;">Products Management</h1>
                    </div>

                    <!-- Alert Message Section -->
                    <?php
                    if (isset($_SESSION['message'])) {
                        echo "
                        <div class='alert alert-{$_SESSION['alert_type']} alert-dismissible fade show custom-alert' role='alert'>
                            {$_SESSION['message']}
                            <div class='progress-bar' id='progress-bar'></div> <!-- Animated line -->
                        </div>";

                        unset($_SESSION['message']);
                        unset($_SESSION['alert_type']);
                    }
                    ?>
                    <!-- End of Alert Message Section -->

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Add Image Banner Button -->
                        <div class="btn btn-circle">
                            <a href="carousel.php" class="btn btn-primary">
                                <i class="fas fa-images">
                                    Add Image Banner
                                </i>
                            </a>
                        </div>
                        
                        <!-- <div class="card-body"> -->
                            <!-- <div class="card"> -->

                                <!-- Food Menu Add -->
                                <?php include("foodMenuBackend.php"); ?>
                                <!-- End of Food Menu CRUD -->

                                <!-- Food Menu Read -->
                                <?php include("readMenu.php"); ?>
                                <!-- End of Food Menu CRUD -->
                            </div>
                       </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

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

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

    <script>
    // Time before auto-close (1 second)
    const timeBeforeClose = 1000;

    // Automatically close the alert after the set time
    setTimeout(() => {
        const alertBox = document.querySelector('.custom-alert');
        if (alertBox) {
            const bsAlert = new bootstrap.Alert(alertBox);
            bsAlert.close(); // Dismiss the alert
        }
    }, timeBeforeClose);
    </script>

</body>

</html>
