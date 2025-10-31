<?php
session_start();

// ✅ If no one is logged in (neither user nor customer)
if (
    (!isset($_SESSION['user_id']) && !isset($_SESSION['customer_id'])) ||
    !isset($_SESSION['gym_id'])
) {
    header("Location: ../login.php?status=error&msg=" . urlencode("Please login to continue."));
    exit();
}

// ✅ Determine the role or type
if (isset($_SESSION['role'])) {
    $role = $_SESSION['role']; // superadmin, admin, trainer
} else {
    $role = "customer"; // default role for customers
}

// ✅ Optional: Restrict access by folder (security)
$currentFile = $_SERVER['PHP_SELF'];

if (strpos($currentFile, '/SuperAdmin/') !== false && $role !== 'superadmin') {
    header("Location: ../login.php?status=error&msg=" . urlencode("Access denied. Super Admin only."));
    exit();
}

if (strpos($currentFile, '/Admin/') !== false && $role !== 'admin') {
    header("Location: ../login.php?status=error&msg=" . urlencode("Access denied. Admin only."));
    exit();
}

if (strpos($currentFile, '/Trainer/') !== false && $role !== 'trainer') {
    header("Location: ../login.php?status=error&msg=" . urlencode("Access denied. Trainer only."));
    exit();
}

if (strpos($currentFile, '/Customer/') !== false && $role !== 'customer') {
    header("Location: ../login.php?status=error&msg=" . urlencode("Access denied. Customer only."));
    exit();
}
