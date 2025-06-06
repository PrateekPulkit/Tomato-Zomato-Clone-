<?php
require_once '../db.php';

header('Content-Type: application/json');

$sql = "SELECT id, name, location, rating, image FROM restaurants";
$result = $conn->query($sql);

$restaurants = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $restaurants[] = $row;
    }
}

echo json_encode($restaurants);
?>
