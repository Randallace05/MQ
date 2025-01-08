<?php
session_start();
include_once "php/config.php";

// Check if user_id exists in the query string
if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    // die("Invalid user ID. <a href='users.php'>Go back</a>.");
    header("location: users.php");
}

$user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
$sql = mysqli_query($conn, "SELECT * FROM tbl_user WHERE unique_id = {$user_id}");

if (mysqli_num_rows($sql) > 0) {
    $row = mysqli_fetch_assoc($sql);
} else {
    header("location: users.php");
    exit;
}
?>


<?php include_once "header.php"; ?>
<body>
  <div class="wrapper">
    <section class="chat-area">
      <header>
        <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
        <img src="php/images/<?php echo htmlspecialchars($row['img']); ?>" alt="profile">
        <div class="details">
          <span><?php echo htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']); ?></span>
          <p><?php echo htmlspecialchars($row['status']); ?></p>
        </div>
      </header>
      <div class="chat-box">

      </div>
      <form action="#" class="typing-area">
        <input type="text" class="incoming_id" name="incoming_id" value="<?php echo htmlspecialchars($user_id); ?>" hidden>
        <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
        <button><i class="fab fa-telegram-plane"></i></button>
      </form>
    </section>
  </div>

  <script src="javascript/chat.js"></script>

</body>
</html>
