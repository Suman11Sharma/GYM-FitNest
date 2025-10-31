<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// ✅ Check if gym is logged in
$gym_id = $_SESSION['gym_id'] ?? null;
if (!$gym_id) {
    die("⚠️ Gym ID not found in session. Please log in again.");
}

// ✅ Check if fee_id is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?status=error&msg=Invalid fee ID");
    exit();
}

$fee_id = intval($_GET['id']);

// ✅ Delete only if the record belongs to this gym
$stmt = $conn->prepare("DELETE FROM visitor_plans WHERE fee_id = ? AND gym_id = ?");
$stmt->bind_param("ii", $fee_id, $gym_id);

if ($stmt->execute()) {
    header("Location: index.php?status=success&msg=Visitor fee deleted successfully");
    exit();
} else {
    header("Location: index.php?status=error&msg=Failed to delete record");
    exit();
}

$stmt->close();
$conn->close();
