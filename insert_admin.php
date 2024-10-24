<?php
// admin/insert_admin.php

require '../backend/config.php';

$username = 'admin'; // Choose a strong username
$password = 'YourSecurePassword'; // Choose a strong password

// Hash the password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Prepare and execute the statement
$stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hashed_password);

if ($stmt->execute()) {
    echo "Admin user created successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
