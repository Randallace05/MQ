<?php
include '../../conn/conn.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Prepare the SQL query
    $sql = "SELECT id, name, codename, expiration_date, stock, is_disabled FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($row = $result->fetch_assoc()) {
        // Return the data as JSON
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'Product not found']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['error' => 'No ID provided']);
}

$conn->close();
?>

