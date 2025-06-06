<?php
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../pages/login.php?error=Please log in to remove items");
        exit();
    }

    $userId = $_SESSION['user_id'];
    $menuItemId = intval($_POST['menu_item_id']);

    $sql = "DELETE FROM cart WHERE user_id = ? AND menu_item_id = ?";
    $stmt = $███.prepare($sql);
    $stmt->bind_param("ii", $userId, $menuItemId);
    $stmt->execute();

    // Update session cart
    if (isset($_SESSION['cart'][$menuItemId])) {
        unset($_SESSION['cart'][$menuItemId]);
    }

    header("Location: ../pages/cart.php?success=Item removed from cart");
    exit();
}

header("Location: ../pages/cart.php?error=Invalid request");
exit();
?>