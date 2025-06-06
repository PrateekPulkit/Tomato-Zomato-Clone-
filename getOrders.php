<?php
require_once '../db.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch orders
$sql = "SELECT id, total_amount, address, payment_method, order_time FROM orders WHERE user_id = ? ORDER BY order_time DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];

while ($row = $result->fetch_assoc()) {
    $orderId = $row['id'];

    // Fetch items for each order
    $itemSql = "
        SELECT m.name, oi.quantity, oi.price 
        FROM order_items oi
        JOIN menu_items m ON oi.menu_item_id = m.id
        WHERE oi.order_id = ?
    ";
    $itemStmt = $conn->prepare($itemSql);
    $itemStmt->bind_param("i", $orderId);
    $itemStmt->execute();
    $itemResult = $itemStmt->get_result();

    $items = [];
    while ($itemRow = $itemResult->fetch_assoc()) {
        $items[] = $itemRow;
    }

    $row['items'] = $items;
    $orders[] = $row;
}

echo json_encode($orders);
?>
