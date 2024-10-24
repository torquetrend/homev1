<?php
// admin/login_process.php
session_start();
require '../config.php'; // Adjust the path as needed

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize inputs
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($username) || empty($password)) {
        header("Location: login.php?error=1");
        exit();
    }

    // Prepare and execute the statement
    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, start a session
            $_SESSION['admin_id'] = $id;
            $_SESSION['admin_username'] = $username;
            $_SESSION['last_activity'] = time(); // Update last activity time stamp
            header("Location: dashboard.php");
            exit();
        } else {
            // Invalid password
            header("Location: login.php?error=1");
            exit();
        }
    } else {
        // User not found
        header("Location: login.php?error=1");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    // Invalid request method
    header("Location: login.php");
    exit();
}
?>
