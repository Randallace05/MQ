<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("../includes/topbar1.php");
include '../conn/conn.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "<script>
        alert('You must log in to view your profile.');
        window.location.href = '../index.php';
    </script>";
    exit;
}

$tbl_user_id = intval($_SESSION['tbl_user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($imageFileType, $allowed_types) && move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        $update_query = $conn->prepare("UPDATE tbl_user SET profile_picture = ? WHERE tbl_user_id = ?");
        $update_query->bind_param("si", $_FILES["profile_picture"]["name"], $tbl_user_id);
        $update_query->execute();
    }
}

$user_query = $conn->prepare("SELECT first_name, last_name, contact_number, email, username, profile_picture FROM tbl_user WHERE tbl_user_id = ?");
$user_query->bind_param("i", $tbl_user_id);
$user_query->execute();
$result = $user_query->get_result();
$user_data = $result->fetch_assoc();

$profile_picture = !empty($user_data['profile_picture']) ? '../uploads/' . htmlspecialchars($user_data['profile_picture']) : '../uploads/default.png';
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
            color: #212529;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .profile-container {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .profile-title {
            font-size: 2rem;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            border: 3px solid #007bff;
        }
        .profile-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .profile-table th, .profile-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .profile-table th {
            width: 40%;
            font-weight: bold;
            color: #495057;
        }
        .profile-actions {
            text-align: center;
            margin-top: 20px;
        }
        .profile-actions a {
            text-decoration: none;
            margin: 0 10px;
            padding: 10px 20px;
            color: #ffffff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }
        .profile-actions a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="profile-container">
    <div class="profile-title">Profile Information</div>
    <img src="<?php echo $profile_picture; ?>" alt="Profile Picture" class="profile-picture">
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="profile_picture" required>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
    <table class="profile-table">
        <tbody>
            <tr>
                <th>First Name</th>
                <td><?php echo htmlspecialchars($user_data['first_name']); ?></td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td><?php echo htmlspecialchars($user_data['last_name']); ?></td>
            </tr>
            <tr>
                <th>Contact Number</th>
                <td><?php echo htmlspecialchars($user_data['contact_number']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($user_data['email']); ?></td>
            </tr>
            <tr>
                <th>Username</th>
                <td><?php echo htmlspecialchars($user_data['username']); ?></td>
            </tr>
        </tbody>
    </table>
    <div class="profile-actions">
        <a href="edit_profile.php">Edit Profile</a>
        <a href="change_password.php">Change Password</a>
        <a href="shop.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Shop</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
