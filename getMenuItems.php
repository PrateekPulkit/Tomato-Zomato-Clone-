<?php
require_once '../db.php';

header('Content-Type: application/json');

if (!isset($_GET['restaurant_id'])) {
    echo json_encode(['error' => 'Restaurant ID is required']);
    exit;
}

$restaurantId = intval($_GET['restaurant_id']);

$sql = "SELECT id, name, description, price, image FROM menu_items WHERE restaurant_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $restaurantId);
$stmt->execute();
$result = $stmt->get_result();

$menuItems = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $menuItems[] = $row;
    }
}

echo json_encode($menuItems);
?>
