<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("../includes/topbar1.php");
// Include database connection
include '../conn/conn.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "
    <script>
        alert('You must log in to view your profile.');
        window.location.href = '../index.php'; // Redirect to login page
    </script>";
    exit;
}

// Get the logged-in user's ID securely from the session
$tbl_user_id = intval($_SESSION['unique_id']);

// Fetch user information
$user_query = $conn->prepare("SELECT first_name, last_name, contact_number, email, username, password FROM tbl_user WHERE tbl_user_id = ?");
$user_query->bind_param("i", $tbl_user_id);
$user_query->execute();
$result = $user_query->get_result();
$user_data = $result->fetch_assoc();

// Handle form submission for updating profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $contact_number = trim($_POST['contact_number']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Update the user's profile
    $update_query = $conn->prepare("UPDATE tbl_user SET first_name = ?, last_name = ?, contact_number = ?, email = ?, username = ?, password = ? WHERE tbl_user_id = ?");
    $update_query->bind_param("ssssssi", $first_name, $last_name, $contact_number, $email, $username, $password, $tbl_user_id);

    if ($update_query->execute()) {
        echo "
        <script>
            alert('Profile updated successfully.');
            window.location.href = 'profile_page.php'; // Reload profile page
        </script>";
    } else {
        echo "
        <script>
            alert('Failed to update profile.');
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .profile-title {
            font-size: 1.8rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .table-container {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="profile-title">Profile Information</div>
    <div class="table-container">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th scope="row">First Name</th>
                    <td><?php echo htmlspecialchars($user_data['first_name']); ?></td>
                </tr>
                <tr>
                    <th scope="row">Last Name</th>
                    <td><?php echo htmlspecialchars($user_data['last_name']); ?></td>
                </tr>
                <tr>
                    <th scope="row">Contact Number</th>
                    <td><?php echo htmlspecialchars($user_data['contact_number']); ?></td>
                </tr>
                <tr>
                    <th scope="row">Email</th>
                    <td><?php echo htmlspecialchars($user_data['email']); ?></td>
                </tr>
                <tr>
                    <th scope="row">Username</th>
                    <td><?php echo htmlspecialchars($user_data['username']); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user_data['first_name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user_data['last_name']); ?>" required>
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-12">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($user_data['contact_number']); ?>" required>
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($user_data['password']); ?>" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="update_profile" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
