<?php
$base_url = '/tomato'; // Set to '' if site is at server root
?>

<style>
    .navbar {
        background-color: var(--dark-secondary);
        padding: 1rem 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
        border-bottom: 3px solid var(--primary);
    }

    .container {
        width: 90%;
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo a {
        color: var(--primary);
        font-size: 1.8rem;
        font-weight: 700;
        text-decoration: none;
        letter-spacing: 0.5px;
        transition: color 0.3s ease;
    }

    .logo a:hover {
        color: var(--primary-light);
    }

    .nav-links {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .nav-links li {
        margin-left: 1.5rem;
    }

    .nav-links a {
        color: var(--light);
        text-decoration: none;
        font-size: 1rem;
        font-weight: 500;
        padding: 0.5rem 0;
        position: relative;
        transition: color 0.3s ease;
    }

    .nav-links a:hover {
        color: var(--primary);
    }

    .nav-links a::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 2px;
        background-color: var(--primary);
        transition: width 0.3s ease;
    }

    .nav-links a:hover::after {
        width: 100%;
    }

    @media (max-width: 768px) {
        .container {
            flex-direction: column;
        }
        
        .logo {
            margin-bottom: 1rem;
        }
        
        .nav-links {
            width: 100%;
            justify-content: space-between;
        }
        
        .nav-links li {
            margin-left: 0;
        }
    }

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
</style>

<nav class="navbar">
    <div class="container">
        <div class="logo">
            <a href="<?php echo $base_url; ?>/index.php">🍅 Tomato</a>
        </div>
        <ul class="nav-links">
            <li><a href="<?php echo $base_url; ?>/index.php">Home</a></li>
            <li><a href="<?php echo $base_url; ?>/pages/restaurantList.php">Restaurants</a></li>
            <li><a href="<?php echo $base_url; ?>/pages/cart.php">Cart</a></li>
            <li><a href="<?php echo $base_url; ?>/pages/orders.php">Orders</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="<?php echo $base_url; ?>/pages/profile.php">Profile</a></li>
                <li><a href="<?php echo $base_url; ?>/pages/logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="<?php echo $base_url; ?>/pages/login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>