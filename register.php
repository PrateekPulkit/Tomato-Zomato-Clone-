<?php
include '../backend/db.php';
include '../components/navbar.php';
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

    .register-container {
        width: 90%;
        max-width: 500px;
        margin: 3rem auto;
        padding: 2.5rem;
        background-color: var(--dark-secondary);
        border-radius: 15px;
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
    }

    .register-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: var(--primary);
    }

    .register-header {
        text-align: center;
        margin-bottom: 2.5rem;
        position: relative;
    }

    .register-header h2 {
        font-size: 2rem;
        color: var(--primary);
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .register-header p {
        color: var(--gray);
        font-size: 1rem;
    }

    .register-header::after {
        content: '';
        display: block;
        width: 60px;
        height: 3px;
        background-color: var(--primary);
        margin: 1rem auto 0;
        border-radius: 3px;
    }

    .register-form {
        width: 100%;
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
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(192, 82, 58, 0.2);
    }

    .form-control::placeholder {
        color: var(--gray);
    }

    .register-btn {
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
        margin-top: 1rem;
        box-shadow: 0 4px 10px rgba(192, 82, 58, 0.3);
    }

    .register-btn:hover {
        background-color: var(--primary-dark);
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(192, 82, 58, 0.4);
    }

    .login-link {
        text-align: center;
        margin-top: 1.5rem;
    }

    .login-link a {
        color: var(--primary);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .login-link a:hover {
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

    .alert-danger {
        background-color: rgba(192, 57, 43, 0.1);
        border: 1px solid rgba(192, 57, 43, 0.2);
        color: #e74c3c;
    }

    .password-requirements {
        font-size: 0.8rem;
        color: var(--gray);
        margin-top: 0.5rem;
    }

    @media (max-width: 768px) {
        .register-container {
            padding: 1.5rem;
            margin: 2rem auto;
        }
        
        .register-header h2 {
            font-size: 1.8rem;
        }
    }
</style>

<div class="register-container">
    <div class="register-header">
        <h2>Create Your Tomato Account</h2>
        <p>Join thousands of food lovers today</p>
    </div>
    
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name     = mysqli_real_escape_string($conn, $_POST['name']);
        $email    = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $checkQuery = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($result) > 0) {
            echo '<div class="alert alert-danger">Email is already registered.</div>';
        } else {
            $insertQuery = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";
            if (mysqli_query($conn, $insertQuery)) {
                echo '<div class="alert alert-success">Registration successful! <a href="login.php">Login Now</a></div>';
            } else {
                echo '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
            }
        }
    } else {
    ?>
    
    <form method="POST" action="register.php" class="register-form">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Enter your full name" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Create a password" required>
            <div class="password-requirements">
                Password must be at least 8 characters long and include a mix of letters, numbers, and special characters.
            </div>
        </div>
        
        <button type="submit" class="register-btn">Create Account</button>
    </form>
    
    <div class="login-link">
        Already have an account? <a href="login.php">Login here</a>
    </div>
    
    <?php
    }
    ?>
</div>

<?php include '../components/footer.php'; ?>