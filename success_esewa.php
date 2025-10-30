<?php
include "database/db_connect.php";

$transaction_id = $_GET['transaction_id'] ?? '';
$transaction_id = strtok($transaction_id, '?'); // remove anything after ?
$transaction_id = trim($transaction_id);

if (!empty($transaction_id)) {
    echo "ssssssssssssss";
    // ✅ Prepare update query
    $update = $conn->prepare("UPDATE visitor_passes SET payment_status='paid' WHERE transaction_id=?");
    $update->bind_param("s", $transaction_id);

    if ($update->execute()) {
        if ($update->affected_rows > 0) {
            // ✅ Row actually updated
            header("Location: index.php?status=success&msg=" . urlencode("✅ Payment Successful! Your gym pass is ready."));
            exit();
        } else {
            // ⚠️ No matching record found
            die("⚠️ No record found for transaction_id: " . htmlspecialchars($transaction_id));
        }
    } else {
        // ❌ SQL error
        die("❌ Database update failed: " . $conn->error);
    }
} else {
    die("⚠️ Invalid transaction reference.");
}
