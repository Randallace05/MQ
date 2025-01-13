const searchBar = document.querySelector(".search input"),
  searchIcon = document.querySelector(".search button"),
  usersList = document.querySelector(".users-list"),
  chatBox = document.querySelector(".chat-box"),
  chatHeader = document.querySelector(".headerr"),
  form = document.querySelector(".typing-area"),
  inputField = form.querySelector(".message-input"),
  incomingIdField = form.querySelector(".incoming_id");

// Toggle search bar visibility
searchIcon.onclick = () => {
  searchBar.classList.toggle("show");
  searchIcon.classList.toggle("active");
  searchBar.focus();
  if (searchBar.classList.contains("active")) {
    searchBar.value = "";
    searchBar.classList.remove("active");
  }
};

// Search functionality
searchBar.onkeyup = () => {
  let searchTerm = searchBar.value;
  if (searchTerm !== "") {
    searchBar.classList.add("active");
  } else {
    searchBar.classList.remove("active");
  }
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/search.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        usersList.innerHTML = data;
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("searchTerm=" + encodeURIComponent(searchTerm));
};

// Load users dynamically
setInterval(() => {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "php/users.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        if (!searchBar.classList.contains("active")) {
          usersList.innerHTML = data;
        }
      }
    }
  };
  xhr.send();
}, 500);



// Handle user click and load their chat
usersList.addEventListener("click", (event) => {
  const chatItem = event.target.closest(".chat-item");
  if (!chatItem) return; // Exit if no valid chat item is clicked.

  const userId = chatItem.getAttribute("data-user-id");
  if (!userId) {
    console.error("User ID not found for the selected chat item.");
    return;
  }

  incomingIdField.value = userId; // Update the hidden field for incoming user ID.

  // Fetch the chat messages for the selected user
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/get-chat.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        chatBox.innerHTML = xhr.response;
        chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom.
      }
    }
  };
  xhr.send("user_id=" + encodeURIComponent(userId));
});

// Handle message sending
form.onsubmit = (e) => {
  e.preventDefault(); // Prevent the form from submitting normally.

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/insert-chat.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        inputField.value = ""; // Clear the input field after sending the message.
        chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom.
      }
    }
  };

  // Send the form data.
  let formData = new FormData(form);
  xhr.send(formData);
};

// Auto-scroll chat box on new messages
chatBox.addEventListener("scroll", () => {
  if (chatBox.scrollTop + chatBox.clientHeight >= chatBox.scrollHeight - 50) {
    chatBox.scrollTop = chatBox.scrollHeight; // Keep it at the bottom.
  }
});
