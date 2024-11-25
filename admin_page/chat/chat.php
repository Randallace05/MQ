<?php 
  session_start();
  include_once "../../conn/conn.php";
  
  // Redirect to login if session is not set
  if(!isset($_SESSION['unique_id'])){
    header("location: ../../index.php");
    exit();
  }
  
  // Check if user_id is set in the URL
  if (!isset($_GET['tbl_user_id'])) {
    header("location: users.php");
    exit();
  }
  
  $user_id = $_GET['tbl_user_id'];

  // Prepare the query to fetch user details
  $stmt = $conn->prepare("SELECT * FROM tbl_user WHERE unique_id = ?");
  $stmt->bind_param('i', $user_id); // Bind the user_id as an integer parameter
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
  } else {
    header("location: users.php");
    exit();
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
          <span><?php echo htmlspecialchars($row['fname'] . " " . $row['lname']); ?></span>
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
