<?php
// Include database connection
include ('../conn/conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch the user's hashed password and role from the database
    $stmt = $conn->prepare("SELECT `password`, `username`, `user_role` FROM `tbl_user` WHERE `username` = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        $stored_password = $row['password'];
        $stored_username = $row['username'];
        $user_role = $row['user_role'];

        // Check if the entered password matches the hashed password in the database
        if (password_verify($password, $stored_password)) {

            // Set session variables upon successful login
            $_SESSION['loggedin'] = true;  // Indicates the user is logged in
            $_SESSION['username'] = $stored_username;  // Store the username in the session
            $_SESSION['user_role'] = $user_role;  // Store the user role in the session

            // Redirect based on the user role
            if ($user_role === 'admin') {
                echo "
                <script>
                    alert('Welcome Admin, Login Successful!');
                    window.location.href = '../admin_page/dashboard/index.php'; // Admin dashboard
                </script>
                ";
            } elseif ($user_role === 'customer') {
                echo "
                <script>
                    alert('Welcome {$stored_username}, Login Successful!'); 
                    window.location.href = '../user_page/shop.php'; // Customer dashboard
                </script>
                ";
            } elseif ($user_role === 'distributor') {
                echo "
                <script>
                    alert('Welcome Distributor, Login Successful!');
                    window.location.href = '../distributor_page/dashboard.php'; // Distributor dashboard
                </script>
                ";
            } else {
                // Handle unexpected roles, if needed
                echo "
                <script>
                    alert('Login Failed, Unknown Role!');
                    window.location.href = '../index.php';
                </script>
                ";
            }
        } else {
            // Incorrect password case
            echo "
            <script>
                alert('Login Failed, Incorrect Password!');
                window.location.href = '../index.php';
            </script>
            ";
        }
    } else {
        // No user found with that username
        echo "
        <script>
            alert('Login Failed, User Not Found!');
            window.location.href = '../index.php';
        </script>
        ";
    }
}
?>
