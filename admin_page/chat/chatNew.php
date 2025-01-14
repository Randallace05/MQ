<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    include_once "php/config.php";

    if(!isset($_SESSION['unique_id'])){
      header("location: login.php");
      exit;
    }
  
    $current_user_role = $_SESSION['user_role'];
    if($current_user_role === 'customer'){
      header("location: index.php"); // Redirect customers to a non-chat page
      exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversations with Distributor</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
    <style>
        * {
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
        }

        body {
            background-color: #fff1f1;
        }

        .containerr {
            display: flex;
            max-width: 1200px;
            height: 800px;
            margin: 0 auto;
            border-radius: 8px;
            overflow: hidden;
        }

        .sidebarr {
            width: 320px;
            background-color: #fff1f1;
            border-right: 1px solid #ffcdd2;
            padding: 20px;
            border-radius: 10px, 0, 0, 10px;
        }

        .main-chat {
            flex: 1;
            background-color: #fff1f1;
            display: flex;
            flex-direction: column;
        }

        .headerr {
            padding: 20px;
            border-bottom: 1px solid #ffcdd2;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: white;
            border-radius: 0 10px 0 0;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-right {
            display: flex;
            gap: 20px;
        }

        .search-box {
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            border: 1px solid #ffcdd2;
            border-radius: 20px;
            background-color: white;
        }

        .chat-list {
            margin-top: 20px;
        }

        .chat-item {
            display: flex;
            align-items: center;
            padding: 10px;
            gap: 10px;
            cursor: pointer;
            border-radius: 8px;
        }

        .chat-item:hover {
            background-color: #ffe6e6;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #ddd;
        }

        .chat-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .message {
            margin: 10px 0;
            max-width: 70%;
        }

        .message.received {
            margin-right: auto;
        }

        .message.sent {
            margin-left: auto;
        }

        .message-bubble {
            padding: 12px 16px;
            border-radius: 16px;
            margin-bottom: 4px;
        }

        .received .message-bubble {
            background-color: white;
            color: #333;
        }

        .sent .message-bubble {
            background-color: #e53935;
            color: white;
        }

        .timestamp {
            font-size: 12px;
            color: #666;
            text-align: right;
        }

        .input-area {
            padding: 20px;
            border-top: 1px solid #ffcdd2;
            display: flex;
            gap: 10px;
            background-color: white;
        }

        .message-input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ffcdd2;
            border-radius: 20px;
            outline: none;
        }

        .send-button {
            background-color: #e53935;
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .action-icon {
            color: #e53935;
            cursor: pointer;
        }

        .chat-header {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            background-color: white;
        }

        .ms-tag {
            background-color: #e53935;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
            margin-left: 8px;
        }
    </style>
</head>
<body>
    <div class="containerr">
        <div class="sidebarr">
            <?php
                $sql = mysqli_query($conn, "SELECT * FROM tbl_user WHERE unique_id = {$_SESSION['unique_id']}");
                if(mysqli_num_rows($sql) > 0){
                    $row = mysqli_fetch_assoc($sql);
                }
            ?>
            <h2>Messages</h2>
            <div class="search-container">
                <div class="search">
                    <span class="text">Select a user to start chat</span>
                    <input type="text" placeholder="Enter name to search...">
                    <button><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="chat-list users-list">
                <!-- User list will be populated here by users.php -->
            </div>
        </div>
        <div class="main-chat">
            <header class="headerr">
                <!-- Header content will be dynamically populated when a chat is selected -->
            </header>
            <div class="chat-content chat-box">
                <div class="default-message">No distributor selected. Click on a user to start chatting.</div>
            </div>
            <form action="php/insert-chat.php" method="POST" class="typing-area">
                <input type="hidden" name="incoming_id" class="incoming_id" value="">
                <input type="text" name="message" class="message-input input-field" placeholder="Type a message here..." autocomplete="off">
                <button class="send-button"><i class="fab fa-telegram-plane"></i></button>
            </form>


        </div>
    </div>

    <script src="javascript/users.js"></script>
    <script src="javascript/chat.js"></script>

</body>
</html>




