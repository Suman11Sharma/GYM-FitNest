<?php
include "../../database/db_connect.php";
session_start();

// ✅ Check session
if (!isset($_SESSION['gym_id'])) {
    header("Location: ../login.php?status=error&msg=" . urlencode("Session expired. Please log in again."));
    exit;
}

$gym_id = $_SESSION['gym_id'];

// ✅ Validate ID
$customer_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($customer_id <= 0) {
    header("Location: index.php?status=error&msg=" . urlencode("Invalid customer ID."));
    exit;
}

// ✅ Prepare delete statement (safe)
$stmt = $conn->prepare("DELETE FROM customers WHERE customer_id = ? AND gym_id = ?");
$stmt->bind_param("ii", $customer_id, $gym_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        header("Location: index.php?status=success&msg=" . urlencode("Customer deleted successfully."));
    } else {
        header("Location: index.php?status=error&msg=" . urlencode("No customer found or unauthorized deletion attempt."));
    }
} else {
    header("Location: index.php?status=error&msg=" . urlencode("Database error: " . $stmt->error));
}
exit;
