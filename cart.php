<?php
session_start();
include '../components/navbar.php';
require_once '../backend/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=Please log in to view cart");
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch cart items
$sql = "SELECT c.menu_item_id, m.name, m.price, m.image, c.quantity 
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

    .cart-container {
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

    .cart-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: var(--primary);
    }

    .cart-header {
        text-align: center;
        margin-bottom: 2.5rem;
        position: relative;
    }

    .cart-header::after {
        content: '';
        display: block;
        width: 80px;
        height: 3px;
        background-color: var(--primary);
        margin: 1rem auto 0;
        border-radius: 3px;
    }

    h2 {
        font-size: 2.2rem;
        color: var(--primary);
        font-weight: 700;
    }

    .cart-items {
        margin-bottom: 2.5rem;
    }

    .cart-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .cart-item img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 1rem;
    }

    .cart-info {
        flex: 1;
    }

    .cart-info h4 {
        margin: 0 0 0.5rem;
        color: var(--light);
    }

    .cart-info p {
        margin: 0 0 0.5rem;
        color: var(--gray);
    }

    .quantity-control {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .quantity-control button {
        background-color: var(--primary);
        color: var(--light);
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 600;
    }

    .quantity-control button:hover {
        background-color: var(--primary-dark);
    }

    .remove-item {
        background-color: transparent;
        color: var(--primary);
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
    }

    .cart-empty {
        text-align: center;
        padding: 3rem 0;
        color: var(--gray);
    }

    .cart-summary {
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

    .checkout-btn {
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

    .checkout-btn:hover {
        background-color: var(--primary-dark);
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(192, 82, 58, 0.4);
    }

    .continue-shopping {
        display: inline-block;
        margin-top: 1.5rem;
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .continue-shopping:hover {
        color: var(--primary-light);
        text-decoration: underline;
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

    @media (max-width: 768px) {
        .cart-container {
            padding: 1.5rem;
            margin: 2rem auto;
        }
        
        h2 {
            font-size: 1.8rem;
        }
    }
</style>

<div class="cart-container">
    <div class="cart-header">
        <h2>Your Cart</h2>
    </div>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>
    
    <div class="cart-items">
        <?php if (count($cartItems) > 0): ?>
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-item">
                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <div class="cart-info">
                        <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                        <p>₹<?php echo htmlspecialchars($item['price']); ?> x <?php echo htmlspecialchars($item['quantity']); ?></p>
                        <div class="quantity-control">
                            <form action="../backend/updateCart.php" method="POST" style="display:flex; gap:0.5rem;">
                                <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item['menu_item_id']); ?>">
                                <button type="submit" name="action" value="decrease" class="decrease">-</button>
                                <span class="quantity"><?php echo htmlspecialchars($item['quantity']); ?></span>
                                <button type="submit" name="action" value="increase" class="increase">+</button>
                            </form>
                        </div>
                    </div>
                    <form action="../backend/removeFromCart.php" method="POST">
                        <input type="hidden" name="menu_item_id" value="<?php echo htmlspecialchars($item['menu_item_id']); ?>">
                        <button type="submit" class="remove-item">✖</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="cart-empty">Your cart is empty. Start adding delicious meals!</div>
        <?php endif; ?>
    </div>
    
    <?php if (count($cartItems) > 0): ?>
        <div class="cart-summary">
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
        
        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
    <?php endif; ?>
    
    <div style="text-align: center;">
        <a href="restaurantList.php" class="continue-shopping">← Continue Shopping</a>
    </div>
</div>

<?php include '../components/footer.php'; ?>