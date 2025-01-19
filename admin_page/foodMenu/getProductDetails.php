<?php
include '../../conn/conn.php';

header('Content-Type: application/json');

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "SELECT p.*, 
                   COALESCE(SUM(pb.stock), 0) as total_stock,
                   GROUP_CONCAT(DISTINCT CONCAT(pb.batch_number, ':', pb.stock, ':', IFNULL(pb.expiration_date, 'N/A')) SEPARATOR '|') as batch_info
            FROM products p
            LEFT JOIN product_batches pb ON p.id = pb.product_id
            WHERE p.id = ?
            GROUP BY p.id";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    try {
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($row = $result->fetch_assoc()) {
            echo json_encode($row);
        } else {
            echo json_encode(['error' => 'Product not found']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['error' => 'No ID provided']);
}

$conn->close();
?>

