<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// ✅ Get gym_id from session
$gym_id = $_SESSION['gym_id'] ?? null;
if (!$gym_id) {
    die("⚠️ Gym ID not found in session. Please log in again.");
}

// ✅ Get fee_id from GET
$fee_id = $_GET['id'] ?? null;
if (!$fee_id) {
    header("Location: index.php?status=error&msg=" . urlencode("Invalid request."));
    exit();
}

// ✅ Delete the visitor plan
$stmt = $conn->prepare("DELETE FROM visitor_plans WHERE fee_id = ? AND gym_id = ?");
$stmt->bind_param("ii", $fee_id, $gym_id);

if ($stmt->execute()) {
    header("Location: index.php?status=success&msg=" . urlencode("Visitor plan deleted successfully!"));
} else {
    header("Location: index.php?status=error&msg=" . urlencode("Failed to delete plan: " . $stmt->error));
}

$stmt->close();
$conn->close();
