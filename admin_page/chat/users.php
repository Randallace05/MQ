<?php
  session_start();
  include_once "php/config.php";

  // Redirect to login if unique_id is not set
  if(!isset($_SESSION['unique_id'])){
    header("location: login.php");
    exit;
  }
?>
<?php include_once "header.php"; ?>
<body>
  <div class="wrapper">
    <section class="users">
      <header>
        <div class="content">
          <?php
            // Use tbl_user_id instead of unique_id
            $sql = mysqli_query($conn, "SELECT * FROM tbl_user WHERE unique_id = {$_SESSION['unique_id']}");

            // Check if the user exists
            if(mysqli_num_rows($sql) > 0){
              $user = mysqli_fetch_assoc($sql);
          ?>
              <img src="php/images/<?php echo htmlspecialchars($user['img'], ENT_QUOTES, 'UTF-8'); ?>" alt="">
              <div class="details">
                <span><?php echo htmlspecialchars($user['first_name'], ENT_QUOTES, 'UTF-8') . " " . htmlspecialchars($user['last_name'], ENT_QUOTES, 'UTF-8'); ?></span>
              </div>
          <?php
            } else {
              echo "<p>User not found. Please log in again.</p>";
              echo '<a href="login.php">Go to Login</a>';
              exit;
            }
          ?>
        </div>
        <a href="php/logout.php?logout_id=<?php echo htmlspecialchars($user['tbl_user_id'], ENT_QUOTES, 'UTF-8'); ?>" class="logout">Logout</a>
      </header>
      <div class="search">
        <span class="text">Select a user to start chat</span>
        <input type="text" placeholder="Enter name to search...">
        <button><i class="fas fa-search"></i></button>
      </div>
      <div class="users-list">

      </div>
    </section>
  </div>

  <script src="javascript/users.js"></script>

</body>
</html>
