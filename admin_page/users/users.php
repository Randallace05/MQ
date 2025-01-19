<?php
include '../../conn/conn.php'; // Database connection

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function fetchUsers($conn, $selectedRole = 'all') {
    if ($selectedRole == 'all') {
        $sql = "SELECT username, user_role, is_active FROM tbl_user WHERE user_role != 'admin'";
        $stmt = $conn->prepare($sql);
    } else {
        $sql = "SELECT username, user_role, is_active FROM tbl_user WHERE user_role = ? AND user_role != 'admin'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $selectedRole);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    $stmt->close();
    return $users;
}

$selectedRole = isset($_GET['user_role']) ? $_GET['user_role'] : 'all';
$users = fetchUsers($conn, $selectedRole);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced User Dashboard</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,700&display=swap" rel="stylesheet">
    <link href="../dashboard/css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fc;
        }

        .table-container {
            margin: 10px auto;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
            width: 95%;
            max-width: 1200px;
        }

        .table-header {
            display: grid;
            grid-template-columns: 2fr 2fr 1fr;
            background-color: #4e73df;
            color: white;
            padding: 10px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
        }

        .table-row {
            display: grid;
            grid-template-columns: 2fr 2fr 1fr;
            padding: 10px;
            transition: background-color 0.3s ease;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }

        .table-row:hover {
            background-color: #f1f1f1;
        }

        .btn {
            padding: 6px 10px;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            width: 90%;
            max-width: 400px;
        }

        .modal-buttons {
            margin-top: 15px;
        }

        .modal-buttons .btn {
            margin: 0 5px;
        }

        @media (max-width: 768px) {
            .table-header, .table-row {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .table-container {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <?php include("../includesAdmin/sidebar.php"); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include("../includesAdmin/topbar.php"); ?>
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Accounts</h1>
                    </div>

                    <form method="GET" action="">
                        <label for="userRoleFilter">Sort:</label>
                        <select name="user_role" id="userRoleFilter" onchange="this.form.submit()">
                            <option value="all" <?php echo $selectedRole == 'all' ? 'selected' : ''; ?>>All</option>
                            <option value="customer" <?php echo $selectedRole == 'customer' ? 'selected' : ''; ?>>Customer</option>
                            <option value="distributor" <?php echo $selectedRole == 'distributor' ? 'selected' : ''; ?>>Distributor</option>
                        </select>
                    </form>

                    <div class="table-container">
                        <div class="table-header">
                            <div>Username</div>
                            <div>Type</div>
                            <div>Action</div>
                        </div>

                        <?php foreach ($users as $user) { ?>
                            <div class="table-row">
                                <div><?php echo htmlspecialchars($user['username']); ?></div>
                                <div><?php echo htmlspecialchars($user['user_role']); ?></div>
                                <div>
                                    <button class="btn <?php echo $user['is_active'] ? 'btn-danger' : 'btn-success'; ?>" 
                                            onclick="showModal('<?php echo $user['username']; ?>', <?php echo $user['is_active'] ? '0' : '1'; ?>)">
                                        <?php echo $user['is_active'] ? 'Disable' : 'Enable'; ?>
                                    </button>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <div id="confirmationModal" class="modal">
                        <div class="modal-content">
                            <p id="modalMessage"></p>
                            <div class="modal-buttons">
                                <button id="confirmButton" class="btn btn-success">Yes</button>
                                <button onclick="closeModal()" class="btn btn-danger">No</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('confirmationModal');
        const modalMessage = document.getElementById('modalMessage');
        const confirmButton = document.getElementById('confirmButton');

        function showModal(username, newStatus) {
            modalMessage.textContent = `Are you sure you want to ${newStatus === 1 ? 'enable' : 'disable'} ${username}?`;
            confirmButton.onclick = () => toggleUserStatus(username, newStatus);
            modal.style.display = 'flex';
        }

        function closeModal() {
            modal.style.display = 'none';
        }

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

        window.onclick = event
