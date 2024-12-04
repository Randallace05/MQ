<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>MQ Kitchen</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="../user_page/assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
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
            height: 30px;
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
            font-weight: 300;
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
            border-radius: 10px;
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

        .left-space, .right-space {
            background-color: #f1f1f1;
            width: 200px;
            height: auto;
        }

        .logo img {
            max-height: 100px;

        }
        .hr {
            color: aqua;
            width: max-content;
        }

        .z-index{
            z-index: 1;
        }
        .search-container {
            position: relative; /* Make this container the reference point for absolute positioning */
        }

        .search-results {
            position: absolute;
            top: 100%; /* Position it directly below the search bar */
            left: 0;
            width: 100%; /* Match the width of the search bar */
            background: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            z-index: 10;
            max-height: 200px; /* Limit height for scrolling */
            overflow-y: auto; /* Add scrolling if results exceed the height */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Add a subtle shadow */
        }

        .search-results .result-item {
            display: flex;
            align-items: center;
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
        }

        .search-results .result-item:last-child {
            border-bottom: none; /* Remove the bottom border for the last item */
        }

        .search-results .result-item img {
            width: 40px; /* Adjust image size */
            height: 40px; /* Make it square */
            margin-right: 10px;
            object-fit: cover; /* Ensure the image is scaled correctly */
            border-radius: 5px; /* Optional: round image corners */
        }

        .search-results .result-item:hover {
            background-color: #f7f7f7; /* Add hover effect */
        }
    </style>
</head>
<body>
    <?php
    include("header.php");

    // Include connection file
    if (file_exists('../conn/conn.php')) {
        include_once '../conn/conn.php'; // Ensure only one inclusion
    } else {
        die("Connection file not found.");
    }


    // Retrieve user ID from session
    $tbl_user_id = $_SESSION['tbl_user_id'] ?? null;

    // Initialize cart count
    $row_count = 0;
    if ($conn && $tbl_user_id) {
        // Secure query to fetch cart count for the specific user
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM cart WHERE tbl_user_id = ?");
        $stmt->bind_param("i", $tbl_user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $data = $result->fetch_assoc();
            $row_count = $data['count'] ?? 0;
        }
        $stmt->close();
    }
    ?>

    <div class="z-index">
        <div class="topbar"></div>
        <div class="header-icons">
            <div class="left-space"></div>
            <div class="logo">
                <img src="../uploads/bgMQ.png" alt="MO Kitchen Logo">
            </div>
            <nav class="nav-links">
                <div class="search-container">
                    <input type="text" class="search-bar" placeholder="What are you looking for?" oninput="fetchSearchResults(this.value)">
                    <button class="search-btn"><i class="fa fa-search"></i></button>
                    <div class="search-results" id="searchResults"></div>
                </div>
            </nav>
            <a href="#"><i class="fa-regular fa-heart"></i>
                <span class="icon-badge">4</span>
            </a>
            <a href="../user_page/cart.php"><i class="fa-solid fa-cart-shopping"></i>
                <span class="icon-badge"><?php echo $row_count; ?></span>
            </a>
            <div class="user-dropdown">
                <a href="#" class="user-icon" onclick="toggleDropdown(event)">
                    <i class="fa-regular fa-user"></i>
                </a>
                <div class="dropdown-content">
                    <a href="../user_page/profile.php">Profile</a>
                    <a href="../user_page/settings.php">Settings</a>
                    <a href="../user_page/logout.php">Logout</a>
                </div>
            </div>
            <div class="right-space"></div>
            <hr>
        </div>
    </div>

    <script>
        function toggleDropdown(event) {
            event.stopPropagation();
            const dropdown = document.querySelector('.dropdown-content');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

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
        };

        function fetchSearchResults(query) {
            const resultsContainer = document.getElementById('searchResults');
            resultsContainer.innerHTML = ''; // Clear previous results

            if (query.trim() === '') {
                return; // Exit if the query is empty
            }

            // Fetch results from the server
            fetch(`search_products.php?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(products => {
                    products.forEach(product => {
                        const item = document.createElement('div');
                        item.className = 'result-item';
                        item.innerHTML = `
                            <img src="../admin_page/foodMenu/uploads/${product.image}" alt="${product.name}">
                            <span>${product.name}</span>
                        `;
                        // Add a click event listener to redirect to the product page
                        item.onclick = () => {
                            window.location.href = `items.php?id=${product.id}`;
                        };
                        resultsContainer.appendChild(item);
                    });
                })
                .catch(error => console.error('Error fetching search results:', error));
        }
    </script>
</body>
</html>
