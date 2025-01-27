<?php
header('Content-Type: application/json');

include("../../conn/conn.php");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch sales data grouped by date, including cart_items and payment_method
    $query = "
        SELECT
            DATE(order_date) AS order_date,
            SUM(total_amount) AS total,
            GROUP_CONCAT(cart_items SEPARATOR ', ') AS cart_items,
            GROUP_CONCAT(payment_method SEPARATOR ', ') AS payment_methods
        FROM transaction_history
        WHERE YEAR(order_date) = YEAR(CURDATE())
        GROUP BY DATE(order_date)
        ORDER BY order_date
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Fetch data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output data as JSON
    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
