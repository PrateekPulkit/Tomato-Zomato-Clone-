<?php
$host = 'localhost';     // Hostname
$user = 'root';          // Default MySQL username in XAMPP
$pass = 'Your password'; // Your MySQL password (update if different)
$dbname = 'tomato';      // Your database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>