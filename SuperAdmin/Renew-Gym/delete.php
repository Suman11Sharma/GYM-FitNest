<?php
include "../../database/user_authentication.php";
include("../../database/db_connect.php"); // ✅ adjust path if needed

// ✅ Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?status=error&msg=Invalid+subscription+ID");
    exit;
}

$id = intval($_GET['id']);

// ✅ Check if record exists before deleting
$checkStmt = $conn->prepare("SELECT subscription_id FROM gym_subscriptions WHERE subscription_id = ?");
$checkStmt->bind_param("i", $id);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows === 0) {
    // Record not found
    header("Location: index.php?status=error&msg=" . urlencode("Subscription updated successfully."));
    exit;
}

// ✅ Proceed with deletion
$stmt = $conn->prepare("DELETE FROM gym_subscriptions WHERE subscription_id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php?status=success&msg=" . urlencode("Subscription deleted successfully"));
} else {
    header("Location: index.php?status=error&msg=" . urlencode("Failed to delete subscription"));
}

$stmt->close();
$conn->close();
exit;
