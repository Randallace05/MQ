const form = document.querySelector(".typing-area");
const inputField = form.querySelector(".input-field");
const sendBtn = form.querySelector("button");
const chatBox = document.querySelector(".chat-box");
const incoming_id = form.querySelector(".incoming_id").value;

form.onsubmit = (e) => {
    e.preventDefault();
};

inputField.focus();

inputField.onkeyup = () => {
    sendBtn.classList.toggle("active", inputField.value.trim() !== "");
};

sendBtn.onclick = () => {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "php/insert-chat.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                inputField.value = "";
                sendBtn.classList.remove("active");
                scrollToBottom();
                fetchMessages();
            } else {
                console.error("Error sending message:", xhr.statusText);
            }
        }
    };
    xhr.onerror = () => {
        console.error("Network error occurred");
    };
    let formData = new FormData(form);
    xhr.send(formData);
};

chatBox.onmouseenter = () => {
    chatBox.classList.add("active");
};

chatBox.onmouseleave = () => {
    chatBox.classList.remove("active");
};

function fetchMessages() {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "php/get-chat.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                chatBox.innerHTML = xhr.response;
                if (!chatBox.classList.contains("active")) {
                    scrollToBottom();
                }
            } else {
                console.error("Error fetching messages:", xhr.statusText);
            }
        }
    };
    xhr.onerror = () => {
        console.error("Network error occurred");
    };
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("incoming_id=" + incoming_id);
}

fetchMessages();
setInterval(fetchMessages, 5000);

function scrollToBottom() {
    chatBox.scrollTop = chatBox.scrollHeight;
}

