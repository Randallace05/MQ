<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['unique_id'])) {
        echo "Session 'unique_id' is not set.";
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    .bg-color {
        background-color: #d24444 !important; /* Override the background color */
        background-image: none !important;
    }.sidebar {
            position: sticky;
            top: 0; /* The sidebar will stick to the top of the viewport */
            height: 100vh; /* Make it full height */
            overflow-y: hidden; /* Remove scrollbar */
            overflow-x: hidden;
            padding-right: 20px; /* Ensure content stays inside */
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar li {
            margin: 0;
            padding: 10px 15px;
        }

        .sidebar a {
            text-decoration: none;
            color: white;
            display: block;
        }

        .sidebar a:hover {
            background-color: #c33a3a; /* Slightly darker shade on hover */
        }
        
    
</style>
<body>
    <!-- Sidebar -->
    <ul class="bg-color navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../dashboard/index.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fa-solid fa-pepper-hot"></i>
        </div>
        <div class="sidebar-brand-text mx-3">MQ KITCHEN</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="../dashboard/index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <!-- <hr class="sidebar-divider"> -->

    <!-- Heading -->
    <div class="sidebar-heading">
        Menu
    </div>

    <!-- Nav Item - Food -->
    <li class="nav-item">
        <a class="nav-link" href="../foodMenu/foodMenu.php">
            <i class="fa-solid fa-jar"></i>
            <span>Food Menu</span></a>
    </li>

    <!-- Nav Item - MAnage Order -->
    <li class="nav-item">
        <a class="nav-link" href="../orders/orders.php">
            <i class="fa-solid fa-cart-shopping"></i>
            <span>Manage Order</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="../inventory/inventory.php">
            <i class="fa-solid fa-cart-shopping"></i>
            <span>Transaction History</span></a>
    </li>


    <!-- Nav Item - Customer Review -->
    <li class="nav-item">
        <a class="nav-link" href="../review/review.php">
            <i class="fa-regular fa-comments"></i>
            <span>Customer Review</span></a>
    </li>

    <!-- Nav Item - Chat -->
    <li class="nav-item">
        <a class="nav-link" href="../chat/chatLP.php">
            <i class="fa-solid fa-message"></i>
            <span>Chat</span></a>
    </li>

    <!-- Nav Item - Users -->
    <li class="nav-item">
        <a class="nav-link" href="../users/users.php">
            <i class="fa-solid fa-users"></i>
            <span>User List</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Others
    </div>

    <!-- Nav Item - Users -->
    <li class="nav-item">
        <a class="nav-link" href="../settings/settings.php">
            <i class="fa-solid fa-gear"></i>
            <span>Settings</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <a href="../../user_page/shop.php">to shop</a>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
    </ul>
    <!-- End of Sidebar -->

</body>
</html>


<?php include("script.php"); ?>


        