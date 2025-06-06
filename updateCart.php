<?php
session_start();
header('Content-Type: application/json');
$db_path = __DIR__ . '/../db.php';
if (!file_exists($db_path)) {
    echo json_encode(['success' => false, 'message' => "Database configuration file not found at $db_path"]);
    exit;
}
require_once $db_path;

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}
$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['itemId'], $_POST['action'])) {
    $itemId = (int)$_POST['itemId'];
    $action = $_POST['action'];

    if ($action === 'increase') {
        $sql = "INSERT INTO cart (user_id, item_id, quantity)
                VALUES (?, ?, 1)
                ON DUPLICATE KEY UPDATE quantity = quantity + 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $userId, $itemId);
    } elseif ($action === 'decrease') {
        // Check current quantity
        $sql = "SELECT quantity FROM cart WHERE user_id = ? AND item_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $userId, $itemId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row && $row['quantity'] > 1) {
            $sql = "UPDATE cart SET quantity = quantity - 1 WHERE user_id = ? AND item_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ii', $userId, $itemId);
        } elseif ($row && $row['quantity'] == 1) {
            $sql = "DELETE FROM cart WHERE user_id = ? AND item_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ii', $userId, $itemId);
        } else {
            echo json_encode(['success' => false, 'message' => 'Item not in cart']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit;
    }

    $stmt->execute();
    $stmt->close();
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn->close();
?>