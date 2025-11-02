<?php
include "../../database/admin_authentication.php";
include "../../database/db_connect.php";

// --- Ensure trainer is logged in ---
if (!isset($_SESSION['trainer_id'])) {
    header("Location: ../login.php");
    exit();
}

$trainer_id = intval($_SESSION['trainer_id']);

// --- Get availability ID from URL ---
if (!isset($_GET['id'])) {
    die("❌ Availability ID not specified.");
}

$availability_id = intval($_GET['id']);

// --- Delete availability if it belongs to this trainer ---
$stmt = $conn->prepare("DELETE FROM trainer_availability WHERE availability_id = ? AND trainer_id = ?");
$stmt->bind_param("ii", $availability_id, $trainer_id);

if ($stmt->execute()) {
    // Success: redirect back to index
    header("Location: index.php?status=success&msg=" . urlencode("Availability deleted successfully"));
} else {
    header("Location: index.php?status=error&msg=" . urlencode("Availability deleted failed"));
}
