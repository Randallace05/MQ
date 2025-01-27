<?php
include '../../conn/conn.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $batchId = $_POST['batchId'];
    $status = $_POST['status'];

    $conn->begin_transaction();

    try {
        if ($status == 1) {
            // Get the product_id for the current batch
            $getProductIdSql = "SELECT product_id FROM product_batches WHERE id = ?";
            $stmt = $conn->prepare($getProductIdSql);
            $stmt->bind_param("i", $batchId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $productId = $row['product_id'];

            // Set all batches for this product to inactive
            $deactivateAllSql = "UPDATE product_batches SET status = 0 WHERE product_id = ?";
            $stmt = $conn->prepare($deactivateAllSql);
            $stmt->bind_param("i", $productId);
            $stmt->execute();
        }

        // Update the status of the specified batch
        $updateBatchSql = "UPDATE product_batches SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($updateBatchSql);
        $stmt->bind_param("ii", $status, $batchId);
        $stmt->execute();

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Batch status updated successfully.']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Failed to update batch status: ' . $e->getMessage()]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();
?>
