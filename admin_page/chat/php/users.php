<?php
    session_start();
    include_once "../../../conn/conn.php"; // Adjust the path as necessary

    $outgoing_id = $_SESSION['unique_id'];
    
    // Prepare the SQL query using PDO
    $sql = "SELECT * FROM tbl_user WHERE unique_id != :outgoing_id ORDER BY tbl_user_id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':outgoing_id', $outgoing_id, PDO::PARAM_INT);
    $stmt->execute();
    
    // Fetch all users
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Include data.php to display users
    $output = ""; // Initialize output to avoid undefined variable errors

    if ($stmt->rowCount() == 0) {
        $output .= "No users are available to chat";
    } else {
        include_once "data.php"; // Now $users is defined and passed to data.php
    }

    echo $output;
?>
