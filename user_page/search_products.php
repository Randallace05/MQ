<?php
include('../conn/conn.php');

$query = $_GET['query'];
$searchResults = [];

if ($query) {
    $stmt = $conn->prepare("SELECT id, name, image FROM products WHERE name LIKE CONCAT('%', ?, '%')");
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $searchResults[] = $row;
    }
}

echo json_encode($searchResults);
?>
