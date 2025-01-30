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

    <!-- chat css -->
    <link rel="stylesheet" href="chat.css">
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
        .notifications-dropdown {
            position: relative;
            display: inline-block;
        }

        .notifications-dropdown .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            width: 300px;
            max-height: 400px;
            overflow-y: auto;
            border-radius: 5px;
        }

        .notifications-dropdown .dropdown-content .dropdown-header {
            padding: 10px;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
        }

        .notifications-dropdown .dropdown-content .dropdown-empty {
            padding: 10px;
            text-align: center;
            color: #666;
        }

        .notifications-dropdown .dropdown-content .notification-item {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
        }

        .notifications-dropdown .dropdown-content .notification-item:hover {
            background-color: #f7f7f7;
        }

        .notifications-dropdown .dropdown-content .notification-item:last-child {
            border-bottom: none;
        }

        #chat-box {
            position: fixed;
            bottom: 10px;
            right: 10px;
            width: 300px;
            height: 400px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            background: #007bff;
            color: white;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-header button {
            background: transparent;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .chat-messages {
            flex-grow: 1;
            padding: 10px;
            overflow-y: auto;
            background: #f9f9f9;
        }

        .message {
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 5px;
            max-width: 80%;
        }

        .user-message {
            background: #007bff;
            color: white;
            align-self: flex-end;
        }

        .admin-message {
            background: #f1f1f1;
            color: black;
            align-self: flex-start;
        }

        .chat-input {
            display: flex;
            padding: 10px;
            border-top: 1px solid #ccc;
            background: #fff;
        }

        .chat-input input {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }

        .chat-input button {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 12px;
            margin-left: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .chat-input button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <?php
    include("header.php");

    include("../conn/conn.php");

    // Retrieve user ID from session
    $tbl_user_id = $_SESSION['tbl_user_id'] ?? null;

    $user_query = $conn->prepare("SELECT profile_picture FROM tbl_user WHERE tbl_user_id = ?");
$user_query->bind_param("i", $tbl_user_id);
$user_query->execute();
$result = $user_query->get_result();
$user_data = $result->fetch_assoc();

$profile_picture = !empty($user_data['profile_picture']) ? '../uploads/' . htmlspecialchars($user_data['profile_picture']) : '../uploads/default.png';

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
    // Initialize wishlist count
    $wishlist_count = 0;
    if ($conn && $tbl_user_id) {
        // Secure query to fetch wishlist count for the specific user
        $wishlist_stmt = $conn->prepare("SELECT COUNT(*) as count FROM wishlist WHERE tbl_user_id = ?");
        $wishlist_stmt->bind_param("i", $tbl_user_id);
        $wishlist_stmt->execute();
        $wishlist_result = $wishlist_stmt->get_result();
        if ($wishlist_result) {
            $wishlist_data = $wishlist_result->fetch_assoc();
            $wishlist_count = $wishlist_data['count'] ?? 0;
        }
        $wishlist_stmt->close();
    }
         // Fetch user role and display message icon conditionally
        if ($conn && $tbl_user_id) {
            $user_role_stmt = $conn->prepare("SELECT user_role FROM tbl_user WHERE tbl_user_id = ?");
            $user_role_stmt->bind_param("i", $tbl_user_id);
            $user_role_stmt->execute();
            $user_role_result = $user_role_stmt->get_result();
            if ($user_role_result) {
                $user_role_data = $user_role_result->fetch_assoc();
                $user_role = $user_role_data['user_role'] ?? null;
            }
            $user_role_stmt->close();
        }
    ?>

    <div class="z-index">
        <div class="topbar"></div>
        <div class="header-icons">
            <div class="left-space"></div>
            <div class="logo">
                <a href="../user_page/shop.php">
                    <img src="../uploads/bgMQ.png" alt="MO Kitchen Logo">
                </a>
            </div>
            <nav class="nav-links">
                <div class="search-container">
                    <input type="text" class="search-bar" placeholder="What are you looking for?" oninput="fetchSearchResults(this.value)">
                    <button class="search-btn"><i class="fa fa-search"></i></button>
                    <div class="search-results" id="searchResults"></div>
                </div>
            </nav>
            <!-- Message for Distributor -->
            <?php
                if ($user_role === 'distributor') {
                    // Fetch unread notification count for the user
                    $notification_count = 0;
                    if ($conn && $tbl_user_id) {
                        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM notifications WHERE tbl_user_id = ? AND is_read = 0");
                        $stmt->bind_param("i", $tbl_user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result) {
                            $data = $result->fetch_assoc();
                            $notification_count = $data['count'] ?? 0;
                        }
                        $stmt->close();
                    }

                    // Display the message icon
                    echo '<a href="#" class="message-icon" id="chat-icon">
                            <i class="fa-regular fa-message"></i>';
                    // Conditionally display the notification count badge
                    if ($notification_count > 0) {
                        echo '<span class="icon-badge" id="notificationCount">' . $notification_count . '</span>';
                    }
                    echo '</a>';
                }
                ?>

            <a href="../user_page/wishlistZ.php">
                <i class="fa-regular fa-heart"></i>
                <span class="icon-badge">
                    <?php echo $wishlist_count; ?></span>
            </a>

            <a href="../user_page/cart.php"><i class="fa-solid fa-cart-shopping"></i>
                <span class="icon-badge">

                    <?php echo $row_count; ?></span>
            </a>
            <div class="user-dropdown">
                <a href="#" class="user-icon" onclick="toggleDropdown(event)">
                <img src="<?php echo $profile_picture; ?>" alt="Profile" class="rounded-circle" width="40" height="40">

                </a>
                <div class="dropdown-content">
                    <a href="../user_page/profile_page.php">Profile</a>
                    <a href="../user_page/transaction_history.php">Orders</a>
                    <a href="../user_page/logout.php">Logout</a>
                </div>
            </div>
            <div class="right-space"></div>
            <hr>
        </div>

        <!-- Chat Box -->
    <div id="chat-box" style="display: none;">
        <div class="chat-header">
            Chat with Admin
            <button id="close-chat">✖</button>
        </div>
        <div class="chat-messages"></div>
        <form action="../admin_page/chat/php/insert-chat.php" class="typing-area">
        <?php
            // Include your database connection file
            include("../conn/conn.php");

            // Query to fetch the admin's unique ID
            $admin_unique_id = null;
            $query = "SELECT unique_id FROM tbl_user WHERE user_role = 'admin' LIMIT 1";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $admin_unique_id = $row['unique_id'];
            }
        ?>

            <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $admin_unique_id; ?>" hidden>
            <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
            <button><i class="fab fa-telegram-plane"></i></button>
        </form>
    </div>



    <script>

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
        function toggleDropdown(event) {
        event.stopPropagation();
        const userDropdown = document.querySelector('.user-dropdown .dropdown-content');
        const notificationsDropdown = document.querySelector('.dropdown-content');


        // Toggle user dropdown
        userDropdown.style.display = userDropdown.style.display === 'block' ? 'none' : 'block';
    }


    document.addEventListener('DOMContentLoaded', function () {
        const chatIcon = document.getElementById('chat-icon');
        const chatBox = document.getElementById('chat-box');
        const closeChatButton = document.getElementById('close-chat');
        const sendMessageButton = document.getElementById('send-message');
        const chatInput = document.getElementById('chat-message');
        const chatMessages = document.querySelector('.chat-messages');

        chatIcon.addEventListener('click', function (e) {
            e.preventDefault();
            chatBox.style.display = 'flex';
        });

        closeChatButton.addEventListener('click', function () {
            chatBox.style.display = 'none';
        });

        sendMessageButton.addEventListener('click', sendMessage);
        chatInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        function sendMessage() {
            const message = chatInput.value.trim();
            if (message) {
                const messageElement = document.createElement('div');
                messageElement.className = 'message user-message';
                messageElement.textContent = message;
                chatMessages.appendChild(messageElement);
                chatInput.value = '';
                chatMessages.scrollTop = chatMessages.scrollHeight;

                // Simulate admin response
                setTimeout(() => {
                    const responseElement = document.createElement('div');
                    responseElement.className = 'message admin-message';
                    responseElement.textContent = "Thank you for your message. We'll respond shortly.";
                    chatMessages.appendChild(responseElement);
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }, 1000);
            }
        }
    });
    </script>
     <!-- script for the caht  -->
    <!-- script for the caht  -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const chatIcon = document.getElementById('chat-icon');
        const chatBox = document.getElementById('chat-box');
        const closeChatButton = document.getElementById('close-chat');
        const form = document.querySelector(".typing-area");
        const incoming_id = form.querySelector(".incoming_id").value;
        const inputField = form.querySelector(".input-field");
        const sendBtn = form.querySelector("button");
        const chatMessages = document.querySelector(".chat-messages");

        chatIcon.addEventListener('click', function (e) {
            e.preventDefault();
            chatBox.style.display = 'flex';
            loadChat();
        });

        closeChatButton.addEventListener('click', function () {
            chatBox.style.display = 'none';
        });

        form.onsubmit = (e) => {
            e.preventDefault();
        }

        inputField.focus();
        inputField.onkeyup = () => {
            if(inputField.value != ""){
                sendBtn.classList.add("active");
            } else {
                sendBtn.classList.remove("active");
            }
        }

        sendBtn.onclick = () => {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../admin_page/chat/php/insert-chat.php", true);
            xhr.onload = () => {
                if(xhr.readyState === XMLHttpRequest.DONE){
                    if(xhr.status === 200){
                        inputField.value = "";
                        scrollToBottom();
                    }
                }
            }
            let formData = new FormData(form);
            xhr.send(formData);
        }

        function loadChat() {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../admin_page/chat/php/get-chat.php", true);
            xhr.onload = () => {
                if(xhr.readyState === XMLHttpRequest.DONE){
                    if(xhr.status === 200){
                        chatMessages.innerHTML = xhr.response;
                        scrollToBottom();
                    }
                }
            }
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            // Include the user_id field in the request payload
            xhr.send("user_id=" + incoming_id);
        }

        setInterval(loadChat, 500);

        function scrollToBottom(){
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    });
</script>


</body>
</html>


