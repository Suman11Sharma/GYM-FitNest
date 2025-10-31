<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// Get transaction ID from eSewa callback
$transaction_id = $_GET['transaction_id'] ?? '';
$transaction_id = strtok($transaction_id, '?'); // remove anything after ?
$transaction_id = trim($transaction_id);

if (!empty($transaction_id)) {
    $stmt = $conn->prepare("UPDATE gym_subscriptions SET payment_status='paid', status='active', updated_at=NOW() WHERE transaction_id=?");
    $stmt->bind_param("s", $transaction_id);
    $stmt->execute();

    // Check affected rows BEFORE closing the statement
    if ($stmt->affected_rows > 0) {
        $stmt->close();
        header("Location: index.php?status=success&msg=" . urlencode("✅ Payment Successful! Your subscription is activated."));
        exit();
    } else {
        $stmt->close();
        header("Location: index.php?status=error&msg=" . urlencode("⚠️ No matching record found for this transaction."));
        exit();
    }
} else {
    die("⚠️ Invalid transaction reference.");
}
