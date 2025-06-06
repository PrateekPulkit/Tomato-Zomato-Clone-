<?php
require_once '../db.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$menuItemId = intval($data['menu_item_id']);
$userId = $_SESSION['user_id'];

$sql = "DELETE FROM cart WHERE user_id = ? AND menu_item_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $userId, $menuItemId);
$stmt->execute();

echo json_encode(['success' => true]);
?>
