<style>
    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background-color: var(--dark-tertiary);
        border-radius: 10px;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .cart-item-info {
        flex: 1;
    }

    .cart-item-name {
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .cart-item-price {
        color: var(--primary);
        font-weight: 600;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .quantity-btn {
        background-color: var(--primary);
        color: var(--light);
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .quantity-btn:hover {
        background-color: var(--primary-dark);
    }

    .quantity {
        width: 40px;
        text-align: center;
        background-color: var(--dark);
        color: var(--light);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 5px;
        padding: 0.2rem;
    }
</style>

<div class="cart-item">
    <div class="cart-item-info">
        <div class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
        <div class="cart-item-price">₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
    </div>
    <div class="quantity-control">
        <button class="quantity-btn minus-btn" data-item-id="<?php echo $itemId; ?>">-</button>
        <input type="text" class="quantity" value="<?php echo $item['quantity']; ?>" readonly>
        <button class="quantity-btn plus-btn" data-item-id="<?php echo $itemId; ?>">+</button>
    </div>
</div>