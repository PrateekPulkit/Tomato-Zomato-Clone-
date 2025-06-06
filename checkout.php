<?php
session_start();
include '../components/navbar.php';
require_once '../backend/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=Please log in to checkout");
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch cart items
$sql = "SELECT c.menu_item_id, m.name, m.price, c.quantity 
        FROM cart c
        JOIN menu_items m ON c.menu_item_id = m.id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
$subtotal = 0;
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
    $subtotal += $row['price'] * $row['quantity'];
}

$deliveryFee = $subtotal > 0 ? 25 : 0; // Flat delivery fee
$taxRate = 0.05; // 5% tax
$tax = $subtotal * $taxRate;
$total = $subtotal + $deliveryFee + $tax;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $address = trim($_POST['address']);
    $paymentMethod = trim($_POST['payment_method']);

    if (empty($address)) {
        $error = "Address is required.";
    } else {
        // Insert order
        $sql = "INSERT INTO orders (user_id, total_amount, address, payment_method, order_time) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("idss", $userId, $total, $address, $paymentMethod);
        $stmt->execute();
        $orderId = $stmt->insert_id;

        // Insert order items
        $sql = "INSERT INTO order_items (order_id, menu_item_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        foreach ($cartItems as $item) {
            $stmt->bind_param("iiid", $orderId, $item['menu_item_id'], $item['quantity'], $item['price']);
            $stmt->execute();
        }

        // Clear cart
        $sql = "DELETE FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        header("Location: orderConfirmation.php?order_id=$orderId");
        exit();
    }
}
?>

<style>
    :root {
        --primary: #c0523a;
        --primary-light: #d46a54;
        --primary-dark: #a04430;
        --dark: #121212;
        --dark-secondary: #1E1E1E;
        --dark-tertiary: #252525;
        --light: #FAFAFA;
        --gray: #9E9E9E;
        --light-gray: #303030;
        --shadow: 0 4px 12px rgba(192, 82, 58, 0.15);
    }

    body {
        background-color: var(--dark);
        color: var(--light);
        font-family: 'Poppins', sans-serif;
    }

    .checkout-container {
        width: 90%;
        max-width: 1000px;
        margin: 3rem auto;
        padding: 2rem;
        background-color: var(--dark-secondary);
        border-radius: 15px;
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
    }

    .checkout-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: var(--primary);
    }

    .checkout-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .checkout-header h2 {
        font-size: 2.2rem;
        color: var(--primary);
        font-weight: 700;
    }

    .order-details {
        margin-bottom: 2rem;
    }

    .order-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .order-summary {
        background-color: var(--dark-tertiary);
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.8rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .summary-row:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .summary-label {
        color: var(--gray);
    }

    .summary-value {
        font-weight: 600;
    }

    .total {
        font-size: 1.2rem;
        color: var(--primary);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--light);
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 1rem;
        background-color: var(--dark-tertiary);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        color: var(--light);
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(192, 82, 58, 0.2);
    }

    .confirm-btn {
        display: block;
        width: 100%;
        padding: 1rem;
        background-color: var(--primary);
        color: var(--light);
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-align: center;
        box-shadow: 0 4px 10px rgba(192, 82, 58, 0.3);
        margin-top: 1rem;
    }

    .confirm-btn:hover {
        background-color: var(--primary-dark);
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(192, 82, 58, 0.4);
    }

    .alert-danger {
        background-color: rgba(192, 57, 43, 0.1);
        border: 1px solid rgba(192, 57, 43, 0.2);
        color: #e74c3c;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    @media (max-width: 768px) {
        .checkout-container {
            padding: 1.5rem;
            margin: 2rem auto;
        }

        .checkout-header h2 {
            font-size: 1.8rem;
        }
    }
</style>

<div class="checkout-container">
    <div class="checkout-header">
        <h2>Checkout</h2>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="order-details">
        <h3>Order Details</h3>
        <?php foreach ($cartItems as $item): ?>
            <div class="order-item">
                <span><?php echo htmlspecialchars($item['name']); ?> x <?php echo htmlspecialchars($item['quantity']); ?></span>
                <span>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="order-summary">
        <div class="summary-row">
            <span class="summary-label">Subtotal</span>
            <span class="summary-value">₹<?php echo number_format($subtotal, 2); ?></span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Delivery Fee</span>
            <span class="summary-value">₹<?php echo number_format($deliveryFee, 2); ?></span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Tax (5%)</span>
            <span class="summary-value">₹<?php echo number_format($tax, 2); ?></span>
        </div>
        <div class="summary-row">
            <span class="summary-label total">Total</span>
            <span class="summary-value total">₹<?php echo number_format($total, 2); ?></span>
        </div>
    </div>

    <form method="POST">
        <div class="form-group">
            <label for="address">Delivery Address</label>
            <textarea id="address" name="address" class="form-control" placeholder="Enter your delivery address" required></textarea>
        </div>
        <div class="form-group">
            <label for="payment_method">Payment Method</label>
            <select id="payment_method" name="payment_method" class="form-control" required>
                <option value="Cash on Delivery">Cash on Delivery</option>
                <option value="Credit Card">Credit Card</option>
                <option value="UPI">UPI</option>
            </select>
        </div>
        <button type="submit" class="confirm-btn">Confirm Order</button>
    </form>
</div>

<?php include '../components/footer.php'; ?>