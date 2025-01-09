<?php 
  session_start();
  include_once "php/config.php";
  if(!isset($_SESSION['unique_id'])){
    header("location: login.php");
    exit();
  }
?>
<?php include_once "header.php"; ?>
<body>
  <div class="wrapper">
    <section class="chat-area">
      <header>
        <?php 
          if(isset($_GET['user_id'])) {
            $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
            $sql = "SELECT * FROM tbl_user WHERE unique_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            if ($stmt) {
              mysqli_stmt_bind_param($stmt, "s", $user_id);
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);
              if(mysqli_num_rows($result) > 0){
                $user = mysqli_fetch_assoc($result);
              } else {
                echo "User not found.";
                exit();
              }
              mysqli_stmt_close($stmt);
            } else {
              header("location: users.php");
            }
          } else {
            echo "No user selected. Please go back and select a user to chat with.";
            exit();
          }
        ?>
        <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
        <img src="php/images/<?php echo htmlspecialchars($user['img']); ?>" alt="">
        <div class="details">
          <span><?php echo htmlspecialchars($user['first_name'] . " " . $user['last_name']); ?></span>
          <p><?php echo htmlspecialchars($user['status']); ?></p>
        </div>
      </header>
      <div class="chat-box">
        <!-- Chat messages will be loaded here dynamically -->
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

