<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .topbar {
            background-color: black;
            height: 40px; 
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: white;
            margin: 0 auto;
            max-width: 1200px;
        }

        .logo {
            margin-right: 30px; 
        }

        .logo img {
            max-height: 50px;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            flex-grow: 1; /* This will make the nav-links take up space in the center */
            justify-content: center;
        }

        .nav-links a {
            text-decoration: none;
            color: black;
            font-weight: 500;
        }

        .header-icons {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* Search bar with icon inside */
        .search-container {
            display: flex;
            align-items: center;
            position: relative;
        }

        .search-bar {
            padding: 10px 40px 10px 20px;
            border: 1px solid #ccc;
            border-radius: 20px;
            width: 250px; 
            background-color: #f7f7f7;
        }

        .search-btn {
            position: absolute;
            right: 10px;
            border: none;
            background: none;
            font-size: 18px;
            cursor: pointer;
            color: black;
        }

        .header-icons a, .search-btn {
            text-decoration: none;
            color: black;
            font-size: 20px;
        }

        /* Thin icons */
        .fa-thin {
            font-weight: 300;
        }

        .icon-badge {
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            position: absolute;
            top: -10px;
            right: -10px;
        }

        .header-icons a {
            position: relative;
        }

        .user-dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none; 
            position: absolute;
            right: 0; 
            background-color: white; 
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1; 
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1; 
        }

        .user-icon {
            cursor: pointer;
        }
    </style>
    <title>Dropdown Example</title>
</head>
<body>

<!-- start include header -->
<?php include("header.php"); ?> 
<!-- end include header -->

<div class="topbar"></div>
<div class="header">
    <div class="logo">
        <img src="uploads/bgMQ.png" alt="MO Kitchen Logo">
    </div>
    <nav class="nav-links">
        <a href="../user_page/shop.php">Home</a>
        <a href="#">Contact</a>
        <a href="index.php">Sign Up</a>
    </nav>
    <div class="header-icons">
        <div class="search-container">
            <input type="text" class="search-bar" placeholder="What are you looking for?">
            <button class="search-btn"><i class="fa fa-search"></i></button>
        </div>
        <a href="#"><i class="fa-regular fa-heart"></i>
            <span class="icon-badge">4</span></a>
        <a href="../user_page/cart.php"><i class="fa-solid fa-cart-shopping"></i>
            <span class="icon-badge">2</span></a>
        
        <!-- User Icon with Dropdown -->
        <div class="user-dropdown">
            <a href="#" class="user-icon" onclick="toggleDropdown(event)">
                <i class="fa-regular fa-user"></i>
            </a>
            <div class="dropdown-content">
                <a href="../user_page/profile.php">Profile</a>
                <a href="../user_page/settings.php">Settings</a>
                <a href="logout.php">Logout</a>

            </div>
        </div>
    </div>
</div>
<hr>

<script>
    function toggleDropdown(event) {
        event.stopPropagation(); // Prevent the click event from bubbling up to the window
        const dropdown = document.querySelector('.dropdown-content');
        
        // Toggle visibility of dropdown
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }

    // Close dropdown if clicking outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.user-icon')) {
            const dropdowns = document.getElementsByClassName("dropdown-content");
            for (let i = 0; i < dropdowns.length; i++) {
                const openDropdown = dropdowns[i];
                if (openDropdown.style.display === 'block') {
                    openDropdown.style.display = 'none';
                }
            }
        }
    }
</script>

</body>
</html>
