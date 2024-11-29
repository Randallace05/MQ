<?php
// Start the session
session_start();

// Destroy all session variables
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect the user to the login page or homepage
echo "
<script>
    alert('You have successfully logged out!');
    window.location.href = '../index.php'; // Redirect to the login page
</script>
";
?>
