<?php 


include_once "../../conn/conn.php"; 
include_once "header.php"; 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users - Chat Application</title>
    <!-- Include your CSS files here -->
    <link rel="stylesheet" href="path/to/your/styles.css">
</head>
<body>
    <div class="wrapper">
        <section class="users">
            <header>
                <div class="content">
                    <?php 
                        // Use PDO for the query
                        $sql = "SELECT * FROM tbl_user WHERE unique_id = :unique_id";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':unique_id', $outgoing_id, PDO::PARAM_STR); // Assuming unique_id is a string
                        $stmt->execute();

                        if ($stmt->rowCount() > 0) {
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        } else {
                            echo "User not found.";
                            exit();
                        }
                    ?>
                    <img src="php/images/<?php echo htmlspecialchars($row['img']); ?>" alt="User Image">
                    <div class="details">
                        <span><?php echo htmlspecialchars($row['fname'] . " " . $row['lname']); ?></span>
                        <p><?php echo htmlspecialchars($row['status']); ?></p>
                    </div>
                </div>
                <a href="php/logout.php?logout_id=<?php echo htmlspecialchars($row['unique_id']); ?>" class="logout">Logout</a>
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
