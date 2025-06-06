<?php
session_start();
require_once '../db.php';

// Enable error reporting for debugging
ini_set('display_errors', 0); // Set to 1 for direct error output
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set JSON header for AJAX
header('Content-Type: application/json');

// Debugging: Log request data and session
file_put_contents('debug.log', "=== New Request ===\n", FILE_APPEND);
file_put_contents('debug.log', "POST Data: " . print_r($_POST, true) . "\n", FILE_APPEND);
file_put_contents('debug.log', "Session Data: " . print_r($_SESSION, true) . "\n", FILE_APPEND);

// Check database connection
if (!$conn || $conn->connect_error) {
    file_put_contents('debug.log', "Database connection failed: " . ($conn ? $conn->connect_error : 'No connection object') . "\n", FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    file_put_contents('debug.log', "Invalid request method: " . $_SERVER['REQUEST_METHOD'] . "\n", FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

if (!isset($_SESSION['user_id'])) {
    file_put_contents('debug.log', "No user_id in session\n", FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Please log in to add items to cart']);
    exit();
}

$userId = intval($_SESSION['user_id']);
$menuItemId = intval($_POST['id'] ?? 0);
$name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
$price = floatval($_POST['price'] ?? 0);

// Log input for debugging
file_put_contents('debug.log', "Input: ID=$menuItemId, Name=$name, Price=$price, User=$userId\n", FILE_APPEND);

// Validate input
if ($menuItemId <= 0 || empty($name) || $price <= 0) {
    file_put_contents('debug.log', "Invalid input: ID=$menuItemId, Name=$name, Price=$price\n", FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Invalid menu item data']);
    exit();
}

// Verify user exists
$sql = "SELECT id FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    file_put_contents('debug.log', "Prepare failed for users query: " . $conn->error . "\n", FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit();
}
$stmt->bind_param("i", $userId);
$stmt->execute();
if ($stmt->get_result()->num_rows === 0) {
    file_put_contents('debug.log', "User ID=$userId not found\n", FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit();
}

// Fetch image from menu_items
$sql = "SELECT image FROM menu_items WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    file_put_contents('debug.log', "Prepare failed for menu_items query: " . $conn->error . "\n", FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit();
}
$stmt->bind_param("i", $menuItemId);
if (!$stmt->execute()) {
    file_put_contents('debug.log', "Execute failed for menu_items query: " . $stmt->error . "\n", FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit();
}
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$image = $row['image'] ?: '../assets/images/default.jpg';

// Check if item exists in menu_items
if (!$row) {
    file_put_contents('debug.log', "Menu item ID=$menuItemId not found\n", FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Menu item not found']);
    exit();
}

// Check if item already exists in cart
$sql = "SELECT id, quantity FROM cart WHERE user_id = ? AND menu_item_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    file_put_contents('debug.log', "Prepare failed for cart query: " . $conn->error . "\n", FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit();
}
$stmt->bind_param("ii", $userId, $menuItemId);
if (!$stmt->execute()) {
    file_put_contents('debug.log', "Execute failed for cart query: " . $stmt->error . "\n", FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit();
}
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update quantity
    $row = $result->fetch_assoc();
    $newQuantity = $row['quantity'] + 1;
    $sql = "UPDATE cart SET quantity = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        file_put_contents('debug.log', "Prepare failed for cart update: " . $conn->error . "\n", FILE_APPEND);
        echo json_encode(['success' => false, 'message' => 'Database error']);
        exit();
    }
    $stmt->bind_param("ii", $newQuantity, $row['id']);
    if (!$stmt->execute()) {
        file_put_contents('debug.log', "Update failed: " . $stmt->error . "\n", FILE_APPEND);
        echo json_encode(['success' => false, 'message' => 'Failed to update cart']);
        exit();
    }
    file_put_contents('debug.log', "Updated cart item: ID=$menuItemId, User=$userId, Quantity=$newQuantity\n", FILE_APPEND);
} else {
    // Insert new item
    $sql = "INSERT INTO cart (user_id, menu_item_id, quantity) VALUES (?, ?, 1)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        file_put_contents('debug.log', "Prepare failed for cart insert: " . $conn->error . "\n", FILE_APPEND);
        echo json_encode(['success' => false, 'message' => 'Database error']);
        exit();
    }
    $stmt->bind_param("ii", $userId, $menuItemId);
    if (!$stmt->execute()) {
        file_put_contents('debug.log', "Insert failed: " . $stmt->error . "\n", FILE_APPEND);
        echo json_encode(['success' => false, 'message' => 'Failed to add to cart']);
        exit();
    }
    file_put_contents('debug.log', "Inserted cart item: ID=$menuItemId, User=$userId\n", FILE_APPEND);
}

// Update session cart
$_SESSION['cart'][$menuItemId] = [
    'id' => $menuItemId,
    'name' => $name,
    'price' => $price,
    'image' => $image,
    'quantity' => isset($newQuantity) ? $newQuantity : 1
];

file_put_contents('debug.log', "Item added: ID=$menuItemId, User=$userId\n", FILE_APPEND);
echo json_encode(['success' => true, 'message' => 'Item added to cart']);
exit();
?>