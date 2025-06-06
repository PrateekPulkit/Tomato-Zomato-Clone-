<?php
include '../backend/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = "SELECT * FROM users WHERE email='$email'";
    $checkResult = mysqli_query($conn, $check);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "Email already registered.";
    } else {
        $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
        if (mysqli_query($conn, $query)) {
            echo "Signup successful. You can now <a href='login.php'>Login</a>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!-- Basic signup form -->
<form method="POST">
    <input type="text" name="name" placeholder="Full Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Signup</button>
</form>
