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
            $sql = mysqli_query($conn, "SELECT * FROM tbl_user WHERE unique_id = {$_SESSION['unique_id']}");
            if(mysqli_num_rows($sql) > 0){
              $user = mysqli_fetch_assoc($sql);
            }
          ?>
          <img src="php/images/<?php echo $user['img']; ?>" alt="">
          <div class="details">
            <span><?php echo $user['first_name']. " " . $user['last_name'] ?></span>
          </div>
        </div>
        <a href="php/logout.php?logout_id=<?php echo $user['unique_id']; ?>" class="logout">Logout</a>
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
