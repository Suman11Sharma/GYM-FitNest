<?php
include "../../database/db_connect.php";

// Get transaction_id from eSewa callback
$transaction_id = $_GET['transaction_id'] ?? '';
$transaction_id = trim($transaction_id);

if (!empty($transaction_id)) {
    // Optionally, you can delete or keep the pending booking
    // Example: keep as pending for retry
    header("Location: customerPage.php?status=error&msg=" . urlencode("⚠️ Payment failed. You can retry booking."));
} else {
    die("⚠️ Invalid transaction reference.");
}
