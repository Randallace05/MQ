<?php 
session_start();
include_once "../../conn/conn.php";
include_once "header.php"; 

// Check if the user is logged in
if (!isset($_SESSION['unique_id'])) {
    echo "Session not set. Please log in.";
    exit();
}

$outgoing_id = $_SESSION['unique_id'];

?>
<body>
  <div class="wrapper">
    <section class="users">
      <header>
        <div class="content">
          <?php 
            // Use PDO for the query
            $sql = "SELECT * FROM tbl_user WHERE unique_id = :unique_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':unique_id', $outgoing_id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                echo "User not found.";
                exit();
            }
          ?>
          <img src="php/images/<?php echo $row['img']; ?>" alt="">
          <div class="details">
            <span><?php echo $row['fname']. " " . $row['lname'] ?></span>
            <p><?php echo $row['status']; ?></p>
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
        <!-- Users list will be populated here -->
      </div>
    </section>
  </div>

  <script src="javascript/users.js"></script>
</body>
</html>
