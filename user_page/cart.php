<?php
ini_set('session.use_only_cookies', 1); // Use cookies only
ini_set('session.cookie_httponly', 1); // Prevent JavaScript access to session
session_start([
    'cookie_lifetime' => 3600, // Session expires in 1 hour
    'cookie_secure' => isset($_SERVER['HTTPS']), // Secure only if HTTPS
]);

?>

<!-- start include header -->
<?php include("../includes/header.php"); ?>
<!-- end include header -->

    <!-- Top Bar Section -->
        <?php include("../includes/topbar1.php"); ?>
    <!-- End Top Bar Section -->

    <!-- Main Container -->
    <div class="container">
        <div class="flex-container">
            <!-- Image Section -->
            <?php include("cartItems.php"); ?>
            <!-- Form Section -->
            <div class="main">




