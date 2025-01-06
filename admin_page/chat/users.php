<?php 
session_start();
include_once "php/config.php";

if (!isset($_SESSION['unique_id'])) {
    header("location: ../../index.php");
    exit;
}

$sql = mysqli_query($conn, "SELECT * FROM tbl_user WHERE tbl_user_id = {$_SESSION['unique_id']}");
if (mysqli_num_rows($sql) > 0) {
    $row = mysqli_fetch_assoc($sql);
} else {
    die("User not found or session expired. Please <a href='../../index.php'>login again</a>.");
}
?>
<?php include_once "header.php"; ?>

<body>
  <div class="wrapper">
    <section class="users">
      <header>
        <div class="content">
          <!-- Display User Info -->
          <div class="details">
            <span><?php echo htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']); ?></span>
            <p><?php echo htmlspecialchars($row['status']); ?></p>
          </div>
        </div>
        <a href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>" class="logout">Logout</a>
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
