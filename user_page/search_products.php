<?php
// Include database connection
include_once '../conn/conn.php';

// Get the search term from the request
$searchTerm = $_GET['query'] ?? '';

if ($conn) {
    // Prepare SQL query to fetch products matching the search term
    $stmt = $conn->prepare("SELECT name, image FROM products WHERE name LIKE ? LIMIT 5");
    $likeTerm = '%' . $searchTerm . '%';
    $stmt->bind_param('s', $likeTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    // Return the data as JSON
    echo json_encode($products);
}
?>
