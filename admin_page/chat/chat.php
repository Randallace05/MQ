<?php 
  session_start();
  include_once "php/config.php";

  // Check if session variable is set
  if (!isset($_SESSION['unique_id'])) {
      header("location: ../../index.php");
      exit;
  }

  // Ensure the `tbl_user_id` is passed in the URL
  if (!isset($_GET['tbl_user_id']) || empty($_GET['tbl_user_id'])) {
      header("location: users.php");
      exit;
  }

  $user_id = mysqli_real_escape_string($conn, $_GET['tbl_user_id']); // Sanitize input

  // Fetch user data
  $sql = mysqli_query($conn, "SELECT * FROM tbl_user WHERE unique_id = '{$user_id}'");
  if ($sql && mysqli_num_rows($sql) > 0) {
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
        <img src="php/images/<?php echo htmlspecialchars($row['img']); ?>" alt="">
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
