<?php
include_once "config.php";

// Test query
$sql = "SELECT * FROM tbl_user";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "User: " . $row['first_name'] . " " . $row['last_name'] . "<br>";
    }
} else {
    echo "Error in query: " . mysqli_error($conn);
}
?>
