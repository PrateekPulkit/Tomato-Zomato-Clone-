<?php
session_start();
include '../components/navbar.php';
require_once '../backend/db.php';

if (!isset($_GET['restaurant_id'])) {
    header("Location: restaurantList.php?error=Restaurant ID required");
    exit();
}

$restaurantId = intval($_GET['restaurant_id']);

// Fetch restaurant details
$sql = "SELECT name, location, rating FROM restaurants WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $restaurantId);
$stmt->execute();
$result = $stmt->get_result();
$restaurant = $result->fetch_assoc();

if (!$restaurant) {
    header("Location: restaurantList.php?error=Restaurant not found");
    exit();
}

// Fetch menu items
$sql = "SELECT id, name, description, price, image FROM menu_items WHERE restaurant_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $restaurantId);
$stmt->execute();
$result = $stmt->get_result();
$menuItems = [];
while ($row = $result->fetch_assoc()) {
    $menuItems[] = $row;
}
?>

<style>
    :root {
        --primary: #c0523a;
        --primary-light: #d46a54;
        --primary-dark: #a04430;
        --dark: #121212;
        --dark-secondary: #1E1E1E;
        --light: #FAFAFA;
        --gray: #9E9E9E;
    }
    body {
        background-color: var(--dark);
        color: var(--light);
        font-family: 'Poppins', sans-serif;
    }
    .restaurant-container {
        width: 90%;
        max-width: 1000px;
        margin: 3rem auto;
        padding: 2rem;
        background-color: var(--dark-secondary);
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(192, 82, 58, 0.15);
    }
    .restaurant-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .restaurant-header h2 {
        color: var(--primary);
        font-size: 2.2rem;
        margin: 0;
    }
    .restaurant-header p {
        color: var(--gray);
        margin: 0.5rem 0;
    }
    .menu-items {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    .alert-success {
        background-color: rgba(39, 174, 96, 0.1);
        border: 1px solid rgba(39, 174, 96, 0.2);
        color: #2ecc71;
    }
    .alert-danger {
        background-color: rgba(192, 57, 43, 0.1);
        border: 1px solid rgba(192, 57, 43, 0.2);
        color: #e74c3c;
    }
</style>

<div class="restaurant-container">
    <div class="restaurant-header">
        <h2><?php echo htmlspecialchars($restaurant['name']); ?></h2>
        <p><?php echo htmlspecialchars($restaurant['location']); ?> | Rating: <?php echo htmlspecialchars($restaurant['rating']); ?></p>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <div class="menu-items">
        <?php if (count($menuItems) > 0): ?>
            <?php foreach ($menuItems as $item): ?>
                <?php include '../components/menuItem.php'; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No menu items available.</p>
        <?php endif; ?>
    </div>
</div>

<?php include '../components/footer.php'; ?>