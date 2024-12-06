<?php
// Database configuration
$host = 'localhost'; // Database host
$db_name = 'exam_ease'; // Database name
$username = 'root'; // Default MySQL username for XAMPP
$password = ''; // Default MySQL password for XAMPP (usually empty)

// Create connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Uncomment the line below for debugging purposes (remove for production)
// echo "Connected successfully";
?>
