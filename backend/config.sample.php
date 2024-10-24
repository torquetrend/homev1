<?php
// backend/config.sample.php

$servername = "localhost"; // Database server
$username = "your_db_username"; // Database username
$password = "your_db_password"; // Database password
$dbname = "torquetrend_db"; // Database name

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set the character set to utf8mb4 for better Unicode support
$conn->set_charset("utf8mb4");
?>
