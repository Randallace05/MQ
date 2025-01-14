<?php
include '../../conn/conn.php'; // Database connection

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Fetch users based on the selected role.
 *
 * @param mysqli $conn The database connection.
 * @param string $selectedRole The role to filter by (default: 'all').
 * @return array The fetched user data.
 */
function fetchUsers($conn, $selectedRole = 'all') {
    // Adjust SQL query based on selected role
    if ($selectedRole == 'all') {
        $sql = "SELECT username, user_role FROM tbl_user WHERE user_role != 'admin'";
        $stmt = $conn->prepare($sql);
    } else {
        $sql = "SELECT username, user_role FROM tbl_user WHERE user_role = ? AND user_role != 'admin'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $selectedRole);
    }

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all rows as an associative array
    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    // Clean up
    $stmt->close();

    return $users;
}

// Determine the selected role
$selectedRole = isset($_GET['user_role']) ? $_GET['user_role'] : 'all';

// Fetch users based on the selected role
$users = fetchUsers($conn, $selectedRole);
?>
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
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include("../includesAdmin/sidebar.php"); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include("../includesAdmin/topbar.php"); ?>
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Accounts</h1>
                    </div>

                    <!-- Dropdown for Filtering -->
                    <form method="GET" action="">
                        <label for="userRoleFilter">Filter:</label>
                        <select name="user_role" id="userRoleFilter" onchange="this.form.submit()">
                            <option value="all" <?php echo $selectedRole == 'all' ? 'selected' : ''; ?>>All</option>
                            <option value="customer" <?php echo $selectedRole == 'customer' ? 'selected' : ''; ?>>Customer</option>
                            <option value="distributor" <?php echo $selectedRole == 'distributor' ? 'selected' : ''; ?>>Distributor</option>
                        </select>
                    </form>

                    <!-- Table Container -->
                    <div class="table-container">
                        <div class="table-header">
                            <span class="table-cell">Username</span>
                            <span class="table-cell">Type</span>
                        </div>

                        <?php foreach ($users as $user) { ?>
                            <div class="table-row">
                                <div class="table-cell">
                                    <div class="avatar"></div>
                                    <?php echo htmlspecialchars($user['username']); ?>
                                </div>
                                <div class="table-cell">
                                    <?php echo htmlspecialchars($user['user_role']); ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>


