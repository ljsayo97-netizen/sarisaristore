<?php
// config.php
$host = 'localhost';
$user = 'user';      // Change to your database username
$password = '';      // Change to your database password
$database = 'user';  // Change to your database name

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8");
?>