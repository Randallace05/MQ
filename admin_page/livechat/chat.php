
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat</title>
    <style>
        /* Basic styling for the chat interface */
        #chat-box {
            width: 300px;
            height: 400px;
            overflow-y: scroll;
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
        #message-input {
            width: 80%;
            padding: 5px;
        }
        #send-button {
            padding: 5px;
        }
    </style>
</head>
<body>
    <div id="chat-box"></div>
    <input type="text" id="message-input" placeholder="Type a message">
    <button id="send-button">Send</button>

    <script>
        const receiverId = 25; // Set to admin's user ID or customer's ID as needed
        const chatBox = document.getElementById("chat-box");
        const messageInput = document.getElementById("message-input");
        const sendButton = document.getElementById("send-button");

        function loadMessages() {
            fetch(`get_messages.php?receiver_id=${receiverId}`)
                .then(response => response.json())
                .then(messages => {
                    chatBox.innerHTML = "";
                    messages.forEach(message => {
                        const messageElement = document.createElement("p");
                        messageElement.textContent = message.sender_id == <?php echo $_SESSION['user_id']; ?> 
                            ? "You: " + message.message 
                            : "Admin: " + message.message;
                        chatBox.appendChild(messageElement);
                    });
                    chatBox.scrollTop = chatBox.scrollHeight;
                });
        }

        sendButton.addEventListener("click", () => {
            const message = messageInput.value;
            if (message) {
                fetch("send_message.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `receiver_id=${receiverId}&message=${message}`
                }).then(() => {
                    messageInput.value = "";
                    loadMessages();
                });
            }
        });

        // Load messages every 2 seconds
        setInterval(loadMessages, 2000);
        loadMessages();
    </script>
</body>
</html>