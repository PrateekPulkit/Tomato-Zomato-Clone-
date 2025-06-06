<style>
 /* Main theme color and variables */
:root {
  --primary: #c0523a;
  --primary-light: #e07963;
  --primary-dark: #a03020;
  --primary-transparent: rgba(192, 82, 58, 0.1);
  --text-dark: #333333;
  --text-light: #ffffff;
  --bg-light: #f9f5f3;
  --border-radius: 8px;
  --box-shadow: 0 4px 12px rgba(192, 82, 58, 0.15);
}

/* Base styles */
body {
  font-family: 'Poppins', sans-serif;
  background-color: var(--bg-light);
  color: var(--text-dark);
  line-height: 1.6;
  margin: 0;
  padding: 0;
}

/* Container - Modified to ensure column layout */
.container {
  max-width: 1200px;
  margin: 2rem auto;
  padding: 0 1.5rem;
  display: flex;
  flex-direction: column;
}

/* Navbar styling */
.navbar {
  background-color: var(--primary);
  padding: 1rem 0;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.navbar .container {
  display: flex;
  flex-direction: row; /* Override the column direction for navbar only */
  justify-content: space-between;
  align-items: center;
  margin: 0 auto;
}

.navbar-brand {
  color: var(--text-light);
  font-size: 1.8rem;
  font-weight: 700;
  text-decoration: none;
}

.navbar-nav {
  display: flex;
  list-style: none;
  gap: 1.5rem;
  margin: 0;
  padding: 0;
}

.navbar-nav a {
  color: var(--text-light);
  text-decoration: none;
  font-weight: 500;
  transition: opacity 0.3s;
}

.navbar-nav a:hover {
  opacity: 0.8;
}

/* Header styling */
h2 {
  color: var(--primary-dark);
  text-align: center;
  font-size: 2.5rem;
  margin-bottom: 2rem;
  position: relative;
  padding-bottom: 15px;
  width: 100%;
}

h2::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 80px;
  height: 3px;
  background-color: var(--primary);
}

/* Menu grid styling - Modified to show items side by side */
.menu-grid {
  width: 100%;
  display: grid;
  grid-template-columns: repeat(3, 1fr); /* Force 3 columns */
  gap: 2rem;
  margin-top: 3rem;
}

/* Menu item card styling */
.menu-item {
  background-color: white;
  border-radius: var(--border-radius);
  overflow: hidden;
  box-shadow: var(--box-shadow);
  transition: transform 0.3s ease;
}

.menu-item:hover {
  transform: translateY(-5px);
}

.menu-item-image {
  height: 180px;
  background-color: var(--primary-transparent);
  overflow: hidden;
}

.menu-item-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.menu-item:hover .menu-item-image img {
  transform: scale(1.1);
}

.menu-item-content {
  padding: 1.5rem;
}

.menu-item-name {
  font-size: 1.2rem;
  font-weight: 600;
  color: var(--primary-dark);
  margin-bottom: 0.5rem;
}

.menu-item-price {
  color: var(--primary);
  font-weight: 700;
  font-size: 1.1rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.menu-item-price::before {
  content: '₹';
}

.menu-item-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 1rem;
}

.add-to-cart {
  background-color: var(--primary);
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
  transition: background-color 0.3s;
}

.add-to-cart:hover {
  background-color: var(--primary-dark);
}

/* Footer styling */
footer {
  background-color: var(--primary-dark);
  color: var(--text-light);
  padding: 2rem 0;
  margin-top: 3rem;
}

.footer-content {
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 2rem;
}

.footer-section {
  flex: 1;
  min-width: 250px;
}

.footer-section h3 {
  font-size: 1.3rem;
  margin-bottom: 1rem;
  position: relative;
  padding-bottom: 10px;
}

.footer-section h3::after {
  content: '';
  position: absolute;
  left: 0;
  bottom: 0;
  width: 50px;
  height: 2px;
  background-color: var(--primary-light);
}

.footer-section p, .footer-section a {
  color: rgba(255, 255, 255, 0.8);
  margin-bottom: 0.5rem;
  display: block;
  text-decoration: none;
}

.footer-section a:hover {
  color: white;
}

.copyright {
  text-align: center;
  padding-top: 2rem;
  margin-top: 2rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

/* Responsive adjustments */
@media (max-width: 992px) {
  .menu-grid {
    grid-template-columns: repeat(2, 1fr); /* 2 columns on medium screens */
  }
}

@media (max-width: 768px) {
  h2 {
    font-size: 2rem;
  }
}

@media (max-width: 576px) {
  .menu-grid {
    grid-template-columns: 1fr; /* 1 column on small screens */
  }
  
  .navbar .container {
    flex-direction: column;
    gap: 1rem;
  }
  
  .navbar-nav {
    justify-content: center;
    padding: 0;
  }
}
</style>

<?php 
  include '../components/navbar.php'; 
  $name = $_GET['name'] ?? 'Restaurant';
?>
<div class="container">
  <h2>Menu for <?php echo htmlspecialchars($name); ?></h2>
  <div class="menu-grid">
    <?php
      // This would normally come from a DB or API. Simulating static menu for now:
      $menu = [
        ['name' => 'Veg Biryani', 'price' => 150],
        ['name' => 'Chicken 65', 'price' => 180],
        ['name' => 'Paneer Butter Masala', 'price' => 200],
        ['name' => 'Masala Dosa', 'price' => 90],
        ['name' => 'Gulab Jamun', 'price' => 60],
      ];
      foreach ($menu as $item) {
        include '../components/restaurantCard.php';
      }
    ?>
  </div>
</div>
<?php include '../components/footer.php'; ?>