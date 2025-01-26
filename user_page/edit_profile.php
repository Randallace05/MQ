<?php
// edit_profile.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("../includes/topbar1.php");
include '../conn/conn.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "<script>
        alert('You must log in to edit your profile.');
        window.location.href = '../index.php';
    </script>";
    exit;
}

$tbl_user_id = intval($_SESSION['tbl_user_id']);

$user_query = $conn->prepare("SELECT first_name, last_name, contact_number, email, username FROM tbl_user WHERE tbl_user_id = ?");
$user_query->bind_param("i", $tbl_user_id);
$user_query->execute();
$result = $user_query->get_result();
$user_data = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $contact_number = trim($_POST['contact_number']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);

    $update_query = $conn->prepare("UPDATE tbl_user SET first_name = ?, last_name = ?, contact_number = ?, email = ?, username = ? WHERE tbl_user_id = ?");
    $update_query->bind_param("sssssi", $first_name, $last_name, $contact_number, $email, $username, $tbl_user_id);

    if ($update_query->execute()) {
        echo "<script>
            alert('Profile updated successfully.');
            window.location.href = 'profile_page.php';
        </script>";
    } else {
        echo "<script>
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
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .edit-profile-container {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        .edit-profile-title {
            font-size: 2rem;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>
</head>
<body>
<div class="edit-profile-container">
    <h1 class="edit-profile-title">Edit Profile</h1>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user_data['first_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user_data['last_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="contact_number" class="form-label">Contact Number</label>
            <input
                type="text"
                class="form-control"
                id="contact_number"
                name="contact_number"
                value="<?php echo htmlspecialchars($user_data['contact_number']); ?>"
                required
                pattern="\d{11}"
                title="Contact number must be exactly 11 digits."
            >
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="profile_page.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
