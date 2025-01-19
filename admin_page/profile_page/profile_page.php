<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['unique_id'])) {
    echo "Session 'unique_id' is not set.";
    exit;
}

// Include database connection
include '../../conn/conn.php';

// Fetch user details
$sql = "SELECT tbl_user_id, unique_id, first_name, last_name, email, contact_number FROM tbl_user WHERE unique_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing the query: " . $conn->error);
}

$stmt->bind_param("s", $_SESSION['unique_id']);

if (!$stmt->execute()) {
    die("Error executing the query: " . $stmt->error);
}

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "No user found.";
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <h1 class="text-center mb-4">Edit Profile</h1>
                <div class="card shadow">
                    <div class="card-body p-4">
                        <form action="update_profile.php" method="POST">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($user['contact_number']); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                            <a href="../dashboard/index.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
