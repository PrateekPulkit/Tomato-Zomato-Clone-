<?php
session_start();
include '../backend/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT name, email FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if (empty($name) || empty($email)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {
        $update_query = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssi", $name, $email, $user_id);
        if ($stmt->execute()) {
            $success_message = "Profile updated successfully!";
            $_SESSION['username'] = $name; // Update session
        } else {
            $error_message = "Error updating profile: " . $stmt->error;
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile - Tomato</title>
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
        
        .profile-container {
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
        
        .profile-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--primary);
        }
        
        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .profile-header h1 {
            font-size: 2.2rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }
        
        .profile-header p {
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
        }
        
        .success-message {
            background-color: rgba(39, 174, 96, 0.1);
            color: #2ecc71;
            padding: 0.8rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border-left: 3px solid #2ecc71;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <h1>Your Profile</h1>
            <p>Manage your account details</p>
        </div>
        
        <?php if (isset($error_message)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php elseif (isset($success_message)): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            
            <button type="submit" class="btn">Update Profile</button>
        </form>
    </div>
</body>
</html>