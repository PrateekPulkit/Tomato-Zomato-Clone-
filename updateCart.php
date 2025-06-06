<?php
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../pages/login.php?error=Please log in to update cart");
        exit();
    }

    $userId = $_SESSION['user_id'];
    $itemId = intval($_POST['item_id']);
    $action = $_POST['action'];

    if ($action === 'increase') {
        $sql = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND menu_item_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $itemId);
        $stmt->execute();
    } elseif ($action === 'decrease') {
        // Get current quantity
        $sql = "SELECT quantity FROM cart WHERE user_id = ? AND menu_item_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $itemId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['quantity'] > 1) {
            $sql = "UPDATE cart SET quantity = quantity - 1 WHERE user_id = ? AND menu_item_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $userId, $itemId);
            $stmt->execute();
        } else {
            $sql = "DELETE FROM cart WHERE user_id = ? AND menu_item_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $userId, $itemId);
            $stmt->execute();
        }
    } elseif ($action === 'remove') {
        $sql = "DELETE FROM cart WHERE user_id = ? AND menu_item_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $itemId);
        $stmt->execute();
    }

    // Update session cart (optional, for consistency)
    $sql = "SELECT c.menu_item_id, m.name, m.price, m.image, c.quantity 
            FROM cart c
            JOIN menu_items m ON c.menu_item_id = m.id
            WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $_SESSION['cart'] = [];
    while ($row = $result->fetch_assoc()) {
        $_SESSION['cart'][$row['menu_item_id']] = [
            'id' => $row['menu_item_id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'image' => $row['image'],
            'quantity' => $row['quantity']
        ];
    }

    header("Location: ../pages/cart.php?success=Cart updated");
    exit();
}

header("Location: ../pages/cart.php?error=Invalid request");
exit();
?>