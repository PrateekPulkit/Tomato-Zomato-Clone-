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
    <?php if (isset($item) && is_array($item) && !empty($item['id'])): ?>
        <img src="<?php echo htmlspecialchars($item['image'] ?? '../assets/images/default.jpg'); ?>" alt="<?php echo htmlspecialchars($item['name'] ?? 'Item'); ?>">
        <div class="menu-info">
            <h4><?php echo htmlspecialchars($item['name'] ?? 'Unknown Item'); ?></h4>
            <p>Price: ₹<?php echo htmlspecialchars($item['price'] ?? '0.00'); ?></p>
            <form class="cart-form">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id'] ?? '0'); ?>">
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($item['name'] ?? ''); ?>">
                <input type="hidden" name="price" value="<?php echo htmlspecialchars($item['price'] ?? '0.00'); ?>">
                <button type="button" class="add-to-cart" data-item-id="<?php echo htmlspecialchars($item['id'] ?? '0'); ?>">Add to Cart</button>
            </form>
        </div>
    <?php else: ?>
        <p>Error: Menu item data not available.</p>
    <?php endif; ?>
</div>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Select all "Add to Cart" buttons
      const addToCartButtons = document.querySelectorAll('.add-to-cart');
  
      addToCartButtons.forEach(button => {
          button.addEventListener('click', async (e) => {
              e.preventDefault();
              
              // Get the form containing the button
              const form = button.closest('.cart-form');
              if (!form) {
                  console.error('Form not found for button:', button);
                  alert('Error: Form not found.');
                  return;
              }
  
              // Log form data for debugging
              const formData = new FormData(form);
              const formDataObj = {};
              formData.forEach((value, key) => { formDataObj[key] = value; });
              console.log('Form Data:', formDataObj);
  
              try {
                  const response = await fetch('/Tomato/backend/addToCart.php', {
                      method: 'POST',
                      body: formData
                  });
  
                  // Log response status and headers
                  console.log('Response Status:', response.status);
                  console.log('Response Headers:', [...response.headers]);
  
                  // Check if response is OK
                  if (!response.ok) {
                      const text = await response.text();
                      console.error('Response Text:', text);
                      throw new Error(`HTTP error! Status: ${response.status}`);
                  }
  
                  const result = await response.json();
                  console.log('Response JSON:', result);
  
                  if (result.success) {
                      alert(result.message || 'Item added to cart!');
                      // Optionally update cart count
                      updateCartCount();
                  } else {
                      alert(result.message || 'Failed to add item to cart.');
                  }
              } catch (error) {
                  console.error('AJAX Error:', error);
                  alert('An error occurred while adding to cart: ' + error.message);
              }
          });
      });
  
      // Function to update cart count (optional)
      async function updateCartCount() {
          try {
              const response = await fetch('/Tomato/backend/getCartItems.php');
              if (!response.ok) {
                  throw new Error('Failed to fetch cart items');
              }
              const items = await response.json();
              const cartCount = items.reduce((sum, item) => sum + item.quantity, 0);
              const cartCountElement = document.querySelector('.cart-count');
              if (cartCountElement) {
                  cartCountElement.textContent = cartCount;
              }
          } catch (error) {
              console.error('Error updating cart count:', error);
          }
      }
  });
  </script>