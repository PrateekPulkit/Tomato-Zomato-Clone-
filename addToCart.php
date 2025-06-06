<?php
require_once '../db.php';
session_start();
header('Content-Type: application/json');

// Check user login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$userId = $_SESSION['user_id'];
$menuItemId = intval($data['menu_item_id']);
$quantity = intval($data['quantity']);

// Check if item already in cart
$sql = "SELECT id FROM cart WHERE user_id = ? AND menu_item_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $userId, $menuItemId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update quantity
    $sql = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND menu_item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $quantity, $userId, $menuItemId);
    $stmt->execute();
} else {
    // Insert new item
    $sql = "INSERT INTO cart (user_id, menu_item_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $userId, $menuItemId, $quantity);
    $stmt->execute();
}

echo json_encode(['success' => true]);
?>
