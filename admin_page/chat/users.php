<?php 
  session_start();
  include_once "php/config.php";
  if(!isset($_SESSION['unique_id'])){
    header("location: login.php");
  }
?>
<?php include_once "header.php"; ?>
<body>
  <div class="wrapper">
    <section class="users">
      <header>
        <div class="content">
          <?php 
            $tbl_user_id = intval($_SESSION['unique_id']);

            // Fetch user information
            $user_query = $conn->prepare("SELECT * FROM tbl_user WHERE tbl_user_id = ?");
            $user_query->bind_param("i", $tbl_user_id);
            $user_query->execute();
            $result = $user_query->get_result();
            $user_data = $result->fetch_assoc();
  
          ?>
          <img src="../../uploads/?php echo $user_data['img']; ?>" alt="">
          <div class="details">
            <span><?php echo $user_data['first_name']. " " . $user_data['last_name'] ?></span>
            <p><?php echo $user_data['status']; ?></p>
          </div>
        </div>
        <a href="php/logout.php?logout_id=<?php echo $user_data['unique_id']; ?>" class="logout">Logout</a>
      </header>
      <div class="search">
        <span class="text">Select an user to start chat</span>
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
