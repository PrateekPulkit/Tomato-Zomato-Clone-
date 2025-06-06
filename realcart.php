<?php include '../components/navbar.php'; ?>

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
    
    <div class="cart-items">
        <?php 
        // Check if cart is empty
        $hasItems = true; // Replace with actual logic to check if cart has items
        
        if ($hasItems) {
            include '../components/cartItem.php';
            // You can include multiple cart items here
        } else {
            echo '<div class="cart-empty">Your cart is empty. Start adding delicious meals!</div>';
        }
        ?>
    </div>
    
    <?php if ($hasItems): ?>
    <div class="cart-summary">
        <div class="summary-row">
            <span class="summary-label">Subtotal</span>
            <span class="summary-value">₹180</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Delivery Fee</span>
            <span class="summary-value">₹25</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Tax</span>
            <span class="summary-value">₹22</span>
        </div>
        <div class="summary-row">
            <span class="summary-label total">Total</span>
            <span class="summary-value total">₹227</span>
        </div>
    </div>
    
    <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
    <?php endif; ?>
    
    <div style="text-align: center;">
        <a href="restaurantList.php" class="continue-shopping">← Continue Shopping</a>
    </div>
</div>

<?php include '../components/footer.php'; ?>