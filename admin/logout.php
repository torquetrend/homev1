<?php
// admin/logout.php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: login.php?message=Logged out successfully.");
exit();
?>
