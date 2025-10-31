<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

$transaction_id = $_GET['pid'] ?? null;
if ($transaction_id) {
    // Update subscription as failed
    $stmt = $conn->prepare("UPDATE gym_subscriptions SET payment_status='failed', updated_at=NOW() WHERE transaction_id=?");
    $stmt->bind_param("s", $transaction_id);
    $stmt->execute();
    $stmt->close();
}

echo "<h3>Payment Failed or Cancelled!</h3>";
echo "<p>Please try again.</p>";
