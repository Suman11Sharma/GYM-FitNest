<?php
include "../../database/db_connect.php";
session_start();

// ✅ Validate session
$gym_id = $_SESSION['gym_id'] ?? null;
if (!$gym_id) {
    die("⚠️ Unauthorized access. Please log in again.");
}

// ✅ Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("⚠️ Invalid request.");
}

$pass_id = (int)$_GET['id'];

// ✅ Delete query
$stmt = $conn->prepare("DELETE FROM visitor_passes WHERE pass_id = ? AND gym_id = ?");
$stmt->bind_param("ii", $pass_id, $gym_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        header("Location: index.php?status=success&msg=" . urlencode(" Record deleted successfully."));
    } else {
        header("Location: index.php?status=error&msg=" . urlencode(" No record found or unauthorized action."));
    }
} else {
    die("❌ Error deleting record: " . $stmt->error);
}

$stmt->close();
$conn->close();
exit;
