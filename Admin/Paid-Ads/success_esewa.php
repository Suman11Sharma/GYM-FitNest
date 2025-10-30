<?php
include "../../database/db_connect.php";
$transaction_id = $_GET['transaction_id'] ?? '';
$transaction_id = strtok($transaction_id, '?'); // remove anything after ?
$transaction_id = trim($transaction_id);


if (!empty($transaction_id)) {
    $stmt = $conn->prepare("UPDATE paid_ads SET payment_status='paid', status='Active', updated_at=NOW() WHERE transaction_id=?");
    $stmt->bind_param("s", $transaction_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: index.php?status=success&msg=" . urlencode("✅ Payment Successful! Your ads is activated."));
    } else {
        header("Location: index.php?status=error&msg=" . urlencode("matching record found!"));
    }
    $stmt->close();
} else {
    die("⚠️ Invalid transaction reference.");
}
