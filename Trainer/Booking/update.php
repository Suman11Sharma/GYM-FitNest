<?php
include "../../database/db_connect.php";
include "../../database/user_authentication.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'] ?? 0;
    $status = $_POST['status'] ?? '';

    if (!$booking_id || empty($status)) {
        die("⚠️ Invalid data.");
    }

    // ✅ Update only the status
    $stmt = $conn->prepare("
        UPDATE trainer_bookings 
        SET status = ?, updated_at = NOW()
        WHERE booking_id = ?
    ");
    $stmt->bind_param("si", $status, $booking_id);

    if ($stmt->execute()) {
        header("Location: index.php?status=success&msg=" . urlencode("Booking status updated successfully!"));
    } else {
        header("Location: index.php?status=error&msg=" . urlencode("Failed to update status."));
    }

    $stmt->close();
}
