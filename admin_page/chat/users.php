<?php 
  session_start();
  include_once '../../conn/conn.php'; // Include the database connection
?>
<?php include_once "header.php"; ?>

<body>
  <div class="wrapper">
    <section class="chat-area">
      <header>
        <?php 
          // Get and validate user_id from the URL
          $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT);

          // Prepare and execute the query using MySQLi
          $stmt = $conn->prepare("SELECT * FROM tbl_user WHERE unique_id = ?");
          $stmt->bind_param("i", $user_id);  // 'i' indicates integer parameter
          $stmt->execute();
          $result = $stmt->get_result();

          // Check if user exists
          if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
          } else {
            header("location: users.php");
            exit(); // Ensure no further code runs
          }
        ?>
        <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
        <img src="php/images/<?php echo htmlspecialchars($row['img']); ?>" alt="User Image">
        <div class="details">
          <span><?php echo htmlspecialchars($row['fname']) . " " . htmlspecialchars($row['lname']); ?></span>
          <p><?php echo htmlspecialchars($row['status']); ?></p>
        </div>
      </header>
      <div class="chat-box">
        <!-- Chat messages will go here -->
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
