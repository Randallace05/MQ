<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Chat Feature</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <style>
        /* Chat Box Styles */
        #chat-box {
            display: none;
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

        .typing-area {
            display: flex;
            width: 100%;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            border-top: 1px solid #ccc;
            background: #fff;
        }

        .typing-area input {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }

        .typing-area button {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 12px;
            margin-left: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
        }

        .typing-area button:hover {
            background: #0056b3;
        }

        .typing-area button.active {
            opacity: 0.8;
        }
    </style>
</head>
<body style="z-index: 1;">
    <!-- Message Icon -->
    <a href="#" id="chat-icon" style="font-size: 24px; cursor: pointer;"><i class="fas fa-comment"></i></a>

    <!-- Chat Box -->
    <div id="chat-box" style="display: none;">
        <div class="chat-header">
            Chat with Admin
            <button id="close-chat">✖</button>
        </div>
        <div class="chat-messages"></div>
        <form action="../admin_page/chat/php/insert-chat.php" class="typing-area">
            <input type="text" class="incoming_id" name="incoming_id" value="1" hidden>
            <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
            <button><i class="fab fa-telegram-plane"></i></button>
        </form>
    </div>

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
                xhr.send("incoming_id="+incoming_id);
            }

            setInterval(loadChat, 500);

            function scrollToBottom(){
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        });
    </script>
</body>
</html>

