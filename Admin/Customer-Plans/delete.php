<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// ✅ Get gym ID from session
$gym_id = $_SESSION['gym_id'] ?? null;
if (!$gym_id) {
    die("⚠️ Gym ID not found in session. Please log in again.");
}

// ✅ Get plan ID from query string
$plan_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($plan_id <= 0) {
    die("⚠️ Invalid Plan ID.");
}

// ✅ Delete only if this plan belongs to this gym
$stmt = $conn->prepare("DELETE FROM customer_plans WHERE plan_id = ? AND gym_id = ?");
$stmt->bind_param("ii", $plan_id, $gym_id);

if ($stmt->execute()) {
    header("Location: index.php?status=success&msg=Customer plan deleted successfully");
    exit();
} else {
    header("Location: index.php?status=error&msg=Failed to delete customer plan");
    exit();
}

$stmt->close();
$conn->close();
