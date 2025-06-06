<?php
session_start();
include '../backend/db.php';

$error_message = ''; // Initialize error_message to empty string

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Use prepared statement to prevent SQL injection
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['name'];
            header("Location: home.php"); // Redirect to home.php
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "User not found.";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to Tomato</title>
    <style>
        :root {
            --primary: #FF5722;
            --primary-light: #FF7D47;
            --primary-dark: #E64A19;
            --dark: #121212;
            --dark-secondary: #1E1E1E;
            --dark-tertiary: #252525;
            --light: #FAFAFA;
            --gray: #9E9E9E;
            --light-gray: #303030;
            --shadow: 0 4px 12px rgba(255, 87, 34, 0.15);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: var(--dark);
            color: var(--light);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
            background-color: var(--dark-secondary);
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.05);
            position: relative;
            overflow: hidden;
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--primary);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h1 {
            font-size: 2.2rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }
        
        .login-header p {
            color: var(--gray);
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
            padding: 0.8rem 1rem;
            background-color: var(--dark-tertiary);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: var(--light);
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.2);
        }
        
        .form-control::placeholder {
            color: var(--gray);
            opacity: 0.7;
        }
        
        .btn {
            width: 100%;
            padding: 1rem;
            background-color: var(--primary);
            color: var(--light);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 10px rgba(255, 87, 34, 0.3);
        }
        
        .btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(255, 87, 34, 0.4);
        }
        
        .error-message {
            background-color: rgba(255, 0, 0, 0.1);
            color: #FF5252;
            padding: 0.8rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border-left: 3px solid #FF5252;
            display: none; /* Hidden by default */
        }
        
        .error-message.show {
            display: block; /* Show only when class 'show' is added */
        }
        
        .signup-link {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--gray);
        }
        
        .signup-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .signup-link a:hover {
            color: var(--primary-light);
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Welcome to Tomato</h1>
            <p>Sign in to continue</p>
        </div>
        
        <?php if (!empty($error_message)): ?>
            <div class="error-message show">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
            
            <button type="submit" class="btn">Login</button>
        </form>
        
        <div class="signup-link">
            Don't have an account? <a href="register.php">Sign up</a>
        </div>
    </div>
</body>
</html>