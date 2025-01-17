<?php
header('Content-Type: application/json');

// Database connection variables
$host = 'localhost';
$dbname = 'login_email_verification';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch sales data grouped by month from the `transaction_history` table
    $query = "
        SELECT DATE(order_date) AS order_date,
               SUM(total_amount) AS total
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

