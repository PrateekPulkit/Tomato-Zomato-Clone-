<?php
foreach ($cartItems as $item) {
?>
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
<?php } ?>