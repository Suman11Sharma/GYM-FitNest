<?php
include "../database/db_connect.php";

$transaction_id = $_GET['transaction_id'] ?? '';
$transaction_id = strtok($transaction_id, '?'); // remove anything after ?
$transaction_id = trim($transaction_id);

if (!empty($transaction_id)) {
    $stmt = $conn->prepare("UPDATE customer_subscriptions 
        SET payment_status='paid', status='active', updated_at=NOW() 
        WHERE transaction_id=?");
    $stmt->bind_param("s", $transaction_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: customerPage.php?status=success&msg=" . urlencode("✅ Payment Successful! Membership renewed."));
    } else {
        header("Location: customerPage.php?status=error&msg=" . urlencode("⚠️ No matching record found."));
    }
    $stmt->close();
} else {
    die("⚠️ Invalid transaction reference.");
}
