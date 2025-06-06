<?php
require_once '../db.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$userId = $_SESSION['user_id'];

$sql = "
    SELECT c.menu_item_id, m.name, m.price, m.image, c.quantity 
    FROM cart c
    JOIN menu_items m ON c.menu_item_id = m.id
    WHERE c.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
    }
}

echo json_encode($cartItems);
?>
