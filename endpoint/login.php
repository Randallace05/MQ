<?php
include ('../conn/conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch the user's hashed password and role from the database
    $stmt = $conn->prepare("SELECT `password`, `username` FROM `tbl_user` WHERE `username` = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        $stored_password = $row['password'];
        $stored_username = $row['username'];

        // Check if the entered password matches the hashed password in the database
        if (password_verify($password, $stored_password)) {

            // Check if the user is "admin" and redirect accordingly
            if ($stored_username === 'admin') {
                echo "
                <script>
                    alert('Welcome Admin, Login Successful!');
                    window.location.href = '../admin_page/dashboard/index.php'; // Admin dashboard
                </script>
                ";
            } else {
                echo "
                <script>
                    alert('Login Successful!');
                    window.location.href = ''; // Regular user dashboard
                </script>
                ";
            }
        } else {
            // Incorrect password case
            echo "
            <script>
                alert('Login Failed, Incorrect Password!');
                window.location.href = 'index.php';
            </script>
            ";
        }
    } else {
        // No user found with that username
        echo "
        <script>
            alert('Login Failed, User Not Found!');
            window.location.href = 'http://localhost/system/login.php';
        </script>
        ";
    }
}
?>
