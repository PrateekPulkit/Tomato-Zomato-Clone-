<?php
require_once '../db.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$userId = $_SESSION['user_id'];
$address = $_POST['address'] ?? '';
$paymentMethod = $_POST['payment_method'] ?? 'Cash on Delivery';

if (empty($address)) {
    echo json_encode(['error' => 'Address is required']);
    exit;
}

// Get cart items
$sql = "
    SELECT c.menu_item_id, m.price, c.quantity 
    FROM cart c
    JOIN menu_items m ON c.menu_item_id = m.id
    WHERE c.user_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
$total = 0;

while ($row = $result->fetch_assoc()) {
    $items[] = $row;
    $total += $row['price'] * $row['quantity'];
}

if (count($items) === 0) {
    echo json_encode(['error' => 'Cart is empty']);
    exit;
}

// Insert order
$sql = "INSERT INTO orders (user_id, total_amount, address, payment_method, order_time) VALUES (?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("idss", $userId, $total, $address, $paymentMethod);
$stmt->execute();

$orderId = $stmt->insert_id;

// Insert order items
$sql = "INSERT INTO order_items (order_id, menu_item_id, quantity, price) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
foreach ($items as $item) {
    $stmt->bind_param("iiid", $orderId, $item['menu_item_id'], $item['quantity'], $item['price']);
    $stmt->execute();
}

// Clear cart
$sql = "DELETE FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();

echo json_encode(['success' => true, 'order_id' => $orderId]);
?>
