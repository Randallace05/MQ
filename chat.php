<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "live_chat";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Session settings
ini_set('session.use_only_cookies', 1); // Use cookies only
ini_set('session.cookie_httponly', 1); // Prevent JavaScript access to session
session_start([
    'cookie_lifetime' => 3600, // Session expires in 1 hour
    'cookie_secure' => isset($_SERVER['HTTPS']), // Secure only if HTTPS
]);

// Handle message submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $sender_id = $_SESSION['user_id'];
    $recipient_id = ($_SESSION['role'] === 'admin') ? $_POST['recipient_id'] : 1; // Admin selects recipient, distributors send to admin
    $message = $conn->real_escape_string($_POST['message']);

    $sql = "INSERT INTO messages (sender_id, recipient_id, message, created_at) VALUES ($sender_id, $recipient_id, '$message', NOW())";
    if (!$conn->query($sql)) {
        die("Error: " . $conn->error);
    }
}

// Fetch messages
$logged_in_user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$sql = ($role === 'admin') 
    ? "SELECT * FROM messages ORDER BY created_at ASC" 
    : "SELECT * FROM messages WHERE sender_id = $logged_in_user_id OR recipient_id = $logged_in_user_id ORDER BY created_at ASC";

$result = $conn->query($sql);
$messages = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .chat-container { width: 50%; margin: 0 auto; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        .chat-box { height: 300px; overflow-y: scroll; border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; }
        .message { margin: 5px 0; }
        .message.admin { color: blue; }
        .message.distributor { color: green; }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-box">
            <?php foreach ($messages as $msg): ?>
                <div class="message <?= $msg['sender_id'] == 1 ? 'admin' : 'distributor'; ?>">
                    <strong><?= $msg['sender_id'] == 1 ? 'Admin' : 'Distributor'; ?>:</strong> <?= htmlspecialchars($msg['message']); ?>
                </div>
            <?php endforeach; ?>
        </div>
        <form method="POST">
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <select name="recipient_id">
                    <option value="2">Distributor 2</option>
                    <option value="3">Distributor 3</option>
                    <!-- Add more distributors as needed -->
                </select>
            <?php endif; ?>
            <input type="text" name="message" placeholder="Type your message here..." required>
            <button type="submit">Send</button>
        </form>
    </div>
</body>
</html>
