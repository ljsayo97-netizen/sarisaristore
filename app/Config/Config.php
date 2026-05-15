<?php
// config.php
$host = 'localhost';
$user = 'user';      
$password = 'password';      
$database = 'user';  

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8");
?>