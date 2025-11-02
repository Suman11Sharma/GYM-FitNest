<?php
include "../../database/db_connect.php";

$transaction_id = $_GET['transaction_id'] ?? '';
$transaction_id = strtok($transaction_id, '?'); // remove anything after ?
$transaction_id = trim($transaction_id);

if (empty($transaction_id)) {
    die("⚠️ Invalid transaction reference.");
}

// Update booking as paid
$stmt = $conn->prepare("
    UPDATE trainer_bookings 
    SET payment_status='paid', updated_at=NOW() 
    WHERE transaction_id=?
");
$stmt->bind_param("s", $transaction_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    header("Location: ../customerPage.php?status=success&msg=" . urlencode("✅ Trainer booking payment successful!"));
} else {
    header("Location: ../customerPage.php?status=error&msg=" . urlencode("⚠️ No matching pending booking found."));
}

$stmt->close();
