<?php
include '../../conn/conn.php';

header('Content-Type: application/json');

if(isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT p.*,
            COALESCE(SUM(CASE WHEN pb.status = 'active' THEN pb.stock ELSE 0 END), 0) as total_stock,
            GROUP_CONCAT(DISTINCT CONCAT(pb.id, ':', pb.stock, ':', IFNULL(pb.expiration_date, 'N/A'), ':', pb.batch_codename, ':', pb.status) SEPARATOR '|') as batch_info
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
            // Initialize batches array
            $row['batches'] = [];

            // Process batch information
            if (!empty($row['batch_info'])) {
                $batches = explode('|', $row['batch_info']);
                foreach ($batches as $batch) {
                    list($batch_id, $stock, $expiration_date, $codename, $status) = explode(':', $batch);
                    $row['batches'][] = [
                        'batch_id' => $batch_id,
                        'stock' => $stock,
                        'expiration_date' => $expiration_date,
                        'codename' => $codename,
                        'is_active' => ($status == 'active')
                    ];
                }
            }

            // Remove the raw batch_info string as it's no longer needed
            unset($row['batch_info']);

            // Ensure numeric values are returned as numbers
            $row['id'] = intval($row['id']);
            $row['price'] = floatval($row['price']);
            $row['total_stock'] = intval($row['total_stock']);

            echo json_encode($row);
        } else {
            echo json_encode([
                'error' => 'Product not found',
                'id' => null,
                'name' => null,
                'price' => null,
                'image' => null,
                'description' => null,
                'total_stock' => 0,
                'batches' => []
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'error' => $e->getMessage(),
            'id' => null,
            'name' => null,
            'price' => null,
            'image' => null,
            'description' => null,
            'total_stock' => 0,
            'batches' => []
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        'error' => 'No ID provided',
        'id' => null,
        'name' => null,
        'price' => null,
        'image' => null,
        'description' => null,
        'total_stock' => 0,
        'batches' => []
    ]);
}

$conn->close();
?>
