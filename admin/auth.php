<?php
// admin/auth.php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // Redirect to the login page if not authenticated
    header("Location: login.php");
    exit();
}

// Optional: Implement session timeout
$inactive = 1800; // 30 minutes

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive)) {
    // Last request was more than 30 minutes ago
    session_unset();     // Unset $_SESSION variable for the run-time
    session_destroy();   // Destroy session data in storage
    header("Location: login.php?timeout=1");
    exit();
}

$_SESSION['last_activity'] = time(); // Update last activity time stamp

?>
