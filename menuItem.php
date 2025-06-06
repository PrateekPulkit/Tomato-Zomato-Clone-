<style>
    .menu-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background-color: #1E1E1E;
        border-radius: 12px;
        margin-bottom: 1rem;
        box-shadow: 0 4px 12px rgba(192, 82, 58, 0.15);
    }
    .menu-item img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 1rem;
    }
    .menu-info {
        flex: 1;
    }
    .menu-info h4 {
        margin: 0 0 0.5rem;
        color: #FAFAFA;
        font-size: 1.2rem;
    }
    .menu-info p {
        margin: 0 0 0.5rem;
        color: #9E9E9E;
    }
    .add-to-cart {
        background-color: #c0523a;
        color: #FAFAFA;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .add-to-cart:hover {
        background-color: #a04430;
        transform: translateY(-2px);
    }
</style>

<div class="menu-item">
    <img src="<?php echo htmlspecialchars($item['image'] ?: '../assets/images/default.jpg'); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
    <div class="menu-info">
        <h4><?php echo htmlspecialchars($item['name']); ?></h4>
        <p><?php echo htmlspecialchars($item['description']); ?></p>
        <p>₹<?php echo htmlspecialchars($item['price']); ?></p>
        <form action="../backend/addToCart.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id']); ?>">
            <input type="hidden" name="name" value="<?php echo htmlspecialchars($item['name']); ?>">
            <input type="hidden" name="price" value="<?php echo htmlspecialchars($item['price']); ?>">
            <button type="submit" class="add-to-cart">Add to Cart</button>
        </form>
    </div>
</div>