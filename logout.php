<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session completely
session_destroy();

// Redirect to login page with message
header("Location:login.php?status=info&msg=" . urlencode("You have been logged out successfully."));
exit;
