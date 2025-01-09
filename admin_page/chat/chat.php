<?php 
  session_start();
  include_once "php/config.php";

  if (!isset($_SESSION['unique_id'])) {
    header("location: login.php");
    exit;  // Ensure that the script stops execution after redirect
  }

  // Get the user_id from the query string (make sure the correct key is used)
  if (isset($_GET['user_id'])) {
    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
  } else {
    // Redirect if user_id is not found in the query string
    header("location: users.php");
    exit;
  }

  // Query the user by unique_id
  $sql = mysqli_query($conn, "SELECT * FROM tbl_user WHERE unique_id = '{$user_id}'");
  
  // Check if the query returns a result
  if (mysqli_num_rows($sql) > 0) {
    $row = mysqli_fetch_assoc($sql);
  } else {
    header("location: users.php");
    exit; // Ensure that the script stops execution if no user is found
  }
?>

<?php include("../includesAdmin/topbar.php"); ?>
<?php include("../includesAdmin/sidebar.php"); ?>
<?php include_once "header.php"; ?>

<body>
  <div class="wrapper">
    <section class="chat-area">
      <header>
        <!-- Display the user's information -->
        <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
        <img src="php/images/<?php echo htmlspecialchars($row['img']); ?>" alt="">
        <div class="details">
          <span><?php echo htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']); ?></span>
          <p><?php echo htmlspecialchars($row['status']); ?></p>
        </div>
      </header>
      <div class="chat-box">
        <!-- Display chat messages here -->
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
