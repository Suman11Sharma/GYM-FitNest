<?php
include "../../database/db_connect.php";
include "../../database/user_authentication.php";

// ✅ Check for valid booking ID
$booking_id = $_GET['id'] ?? 0;
if (!$booking_id) {
    die("⚠️ Invalid booking ID.");
}

// ✅ Delete query using prepared statement
$stmt = $conn->prepare("DELETE FROM trainer_bookings WHERE booking_id = ?");
$stmt->bind_param("i", $booking_id);

if ($stmt->execute()) {
    header("Location: index.php?status=success&msg=" . urlencode("Booking deleted successfully!"));
} else {
    header("Location: index.php?status=error&msg=" . urlencode("Failed to delete booking."));
}

$stmt->close();
exit;
