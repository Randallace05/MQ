<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Chat Feature</title>
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
    </style>
</head>
<body style="z-index: 1;">
    <!-- Message Icon -->
    <a href="#" class="fa fa-message" style="font-size: 24px; cursor: pointer;"></a>

    <!-- Chat Box -->
    <div id="chat-box">
        <div class="chat-header">
            Chat
            <button id="close-chat">✖</button>
        </div>
        <div class="chat-messages"></div>
        <div class="chat-input">
            <input type="text" id="chat-message" placeholder="Type your message..." />
            <button id="send-message">Send</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const messageIcon = document.querySelector('.fa-message');
            const chatBox = document.getElementById('chat-box');
            const closeChatButton = document.getElementById('close-chat');
            const sendMessageButton = document.getElementById('send-message');
            const chatInput = document.getElementById('chat-message');
            const chatMessages = document.querySelector('.chat-messages');

            messageIcon.addEventListener('click', function (e) {
                e.preventDefault();
                chatBox.style.display = 'block';
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
</body>
</html>
