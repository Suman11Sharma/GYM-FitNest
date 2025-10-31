<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// Check if ID is passed
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?status=error&msg=Invalid Ad ID");
    exit();
}

$ad_id = intval($_GET['id']);
$gym_id = $_SESSION['gym_id'] ?? 0; // extra security – only delete their own ad

// Delete ad only if it belongs to the logged-in gym
$stmt = $conn->prepare("DELETE FROM paid_ads WHERE ad_id = ? AND gym_id = ?");
$stmt->bind_param("ii", $ad_id, $gym_id);

if ($stmt->execute()) {
    header("Location: index.php?status=success&msg=Ad deleted successfully.");
} else {
    header("Location: index.php?status=error&msg=Failed to delete ad.");
}

$stmt->close();
$conn->close();
exit();
