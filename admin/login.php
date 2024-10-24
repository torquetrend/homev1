<?php
// admin/login.php
session_start();

// If the admin is already logged in, redirect to the dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Handle timeout message
$timeout = isset($_GET['timeout']) ? true : false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - TorqueTrend</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container" style="max-width: 400px; margin-top: 100px;">
        <h2>Admin Login</h2>
        <?php if (isset($_GET['error'])): ?>
            <div class="error-message">
                <p>Invalid username or password.</p>
            </div>
        <?php endif; ?>
        <?php if ($timeout): ?>
            <div class="error-message">
                <p>Your session has expired due to inactivity. Please log in again.</p>
            </div>
        <?php endif; ?>
        <form action="login_process.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-primary">Login</button>
        </form>
    </div>
</body>
</html>
