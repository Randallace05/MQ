<?php
// ini_set('session.use_only_cookies', 1); // Use cookies only
// ini_set('session.cookie_httponly', 1); // Prevent JavaScript access to session
// session_start([
//     'cookie_lifetime' => 3600, // Session expires in 1 hour
//     'cookie_secure' => isset($_SERVER['HTTPS']), // Secure only if HTTPS
// ]);

// Initialize session
session_start();

// Include the database connection
include('../conn/conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = trim($_POST['username']); // Trim whitespace
    $password = $_POST['password'];

    // Check if both fields are filled
    if (empty($username) || empty($password)) {
        echo "
        <script>
            alert('Please fill in both username and password.');
            window.location.href = '../index.php';
        </script>";
        exit;
    }
    
    // Prepare the SQL query to fetch user data
    $query = "SELECT tbl_user_id AS unique_id, `password`, `username`, `user_role` FROM `tbl_user` WHERE `username` = ?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("s", $username); // Bind username
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user was found
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $stored_password = $user['password'];
        $stored_username = $user['username'];
        $user_role = $user['user_role'];
        $unique_id = $user['unique_id']; // Renamed tbl_user_id to unique_id

        // Verify the entered password against the stored hashed password
        if (password_verify($password, $stored_password)) {
            // Clear previous session data
            session_unset();
            session_destroy();
            session_start();

            // Set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $stored_username;
            $_SESSION['user_role'] = $user_role;
            $_SESSION['unique_id'] = $unique_id; // Use unique_id for consistency

            // Redirect based on user role
            switch ($user_role) {
                case 'admin':
                    echo "
                    <script>
                        alert('Welcome Admin, Login Successful!');
                        window.location.href = '../admin_page/dashboard/index.php'; // Admin dashboard
                    </script>";
                    break;
                case 'customer':
                    echo "
                    <script>
                        alert('Welcome {$stored_username}, Login Successful!');
                        window.location.href = '../user_page/shop.php'; // Customer dashboard
                    </script>";
                    break;
                case 'distributor':
                    echo "
                    <script>
                        alert('Welcome Distributor, Login Successful!');
                        window.location.href = '../distributor_page/landing_page/index.php'; // Distributor dashboard
                    </script>";
                    break;
                default:
                    // Handle unexpected roles
                    echo "
                    <script>
                        alert('Login Failed: Unknown Role!');
                        window.location.href = '../index.php';
                    </script>";
                    break;
            }
        } else {
            // Password mismatch
            echo "
            <script>
                alert('Login Failed: Incorrect Password!');
                window.location.href = '../index.php';
            </script>";
        }
    } else {
        // No user found with the entered username
        echo "
        <script>
            alert('Login Failed: User Not Found!');
            window.location.href = '../index.php';
        </script>";
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // Redirect to index if the script is accessed without a POST request
    header("Location: ../index.php");
    exit;
}

// Close the database connection
$conn->close();
?>
