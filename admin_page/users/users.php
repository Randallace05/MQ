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
        $sql = "SELECT username, user_role, is_active FROM tbl_user WHERE user_role != 'admin'";
        $stmt = $conn->prepare($sql);
    } else {
        $sql = "SELECT username, user_role, is_active FROM tbl_user WHERE user_role = ? AND user_role != 'admin'";
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
    .table-container {
        width: 80%;
        margin: 20px auto;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .table-header, .table-row {
        display: grid;
        grid-template-columns: 2fr 2fr 1fr; /* Adjust column sizes */
        text-align: center;
        padding: 10px 14px;
    }

    .table-header {
        background-color: #007bff;
        color: white;
        font-weight: bold;
    }

    .table-row {
        background-color: #ffffff;
        border-bottom: 1px solid #ddd;
    }

    .table-row:nth-child(even) {
        background-color: #f9f9f9;
    }

    .table-row:last-child {
        border-bottom: none;
    }

    .btn {
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        color: white;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-success {
        background-color: #28a745;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    .btn-danger {
        background-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    /* Center Content */
    .table-header > div, .table-row > div {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        margin-right: 20px;
        margin-left: 20px;
    }

    /* Adjust for Small Screens */
    @media (max-width: 768px) {
        .table-container {
            width: 90%; /* Reduce width for smaller screens */
        }

        .table-header, .table-row {
            grid-template-columns: 1fr; /* Switch to single-column layout */
            text-align: left;
        }

        .table-header > div, .table-row > div {
            justify-content: flex-start;
            padding: 8px 10px;
        }

        .table-row > div {
            display: flex;
            justify-content: space-between;
        }
    }

    @media (max-width: 480px) {
        .btn {
            padding: 4px 8px;
            font-size: 12px;
        }

        .table-header, .table-row {
            padding: 6px 8px;
        }
    }
    :root {
        /* Define theme colors */
        --primary-color: #007bff; /* Adjust to your system's primary color */
        --secondary-color: #f8f9fa; /* Background for dropdown */
        --hover-color:rgb(240, 6, 142); /* Hover state color */
        --border-color: #ddd; /* Border color */
        --text-color: #333; /* Text color */
        --focus-shadow-color: rgba(0, 123, 255, 0.5); /* Focus shadow color */
    }

    form {
        display: inline-block;
        font-family: Arial, sans-serif;
        margin: 10px;
    }

    label {
        font-weight: bold;
        margin-right: 10px;
        color: var(--text-color);
    }

    select {
        padding: 5px 12px;
        border: 1px solid var(--border-color);
        border-radius: 5px;
        background-color: var(--secondary-color);
        font-size: 14px;
        color: var(--text-color);
        cursor: pointer;
        outline: none;
        transition: all 0.3s ease;
    }

    select:hover {
        border-color: var(--primary-color);
        background-color: var(--primary-color);
        color: #fff; /* Text color when hovered */
    }

    select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 5px var(--focus-shadow-color);
    }

    option {
        background-color: var(--secondary-color);
        color: var(--text-color);
    }

    option:hover {
        background-color: var(--hover-color);
        color: #fff;
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
                    <!--                 darrly nakuha mo ba
 -->
                    <!-- Dropdown for Filtering -->
                    <form method="GET" action="">
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
                            <span class="table-cell">Action</span>

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
                                <div class="table-cell">
                                    <button class="btn btn-sm <?php echo $user['is_active'] ? 'btn-danger' : 'btn-success'; ?>" 
                                            onclick="toggleUserStatus('<?php echo $user['username']; ?>', <?php echo $user['is_active'] ? '0' : '1'; ?>)">
                                        <?php echo $user['is_active'] ? 'Disable' : 'Enable'; ?>
                                    </button>
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
    <script>
        function toggleUserStatus(username, newStatus) {
            fetch('toggle_user_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `username=${encodeURIComponent(username)}&status=${newStatus}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to update user status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating user status');
            });
        }

        function setTheme(theme) {
    document.documentElement.style.setProperty('--primary-color', theme.primaryColor);
    document.documentElement.style.setProperty('--secondary-color', theme.secondaryColor);
    document.documentElement.style.setProperty('--hover-color', theme.hoverColor);
    document.documentElement.style.setProperty('--text-color', theme.textColor);
    document.documentElement.style.setProperty('--border-color', theme.borderColor);
}
    </script>
</body>
</html>

