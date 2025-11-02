<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// ✅ Get gym_id from session
$gym_id = $_SESSION['gym_id'] ?? 0;

// Get user input
$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
$plan_id = isset($_POST['plan_id']) ? intval($_POST['plan_id']) : 0;
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
$payment_status = isset($_POST['payment_status']) ? $_POST['payment_status'] : 'paid';
$transaction_id = isset($_POST['transaction_id']) ? $_POST['transaction_id'] : 'cash';

// Basic validation
if ($user_id && $plan_id && $gym_id && $start_date && $end_date && $amount) {
    $stmt = $conn->prepare("INSERT INTO customer_subscriptions 
        (user_id, plan_id, gym_id, start_date, end_date, amount, payment_status, transaction_id, status, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active', NOW(), NOW())");
    $stmt->bind_param("iiissdss", $user_id, $plan_id, $gym_id, $start_date, $end_date, $amount, $payment_status, $transaction_id);

    if ($stmt->execute()) {
        header("Location: index.php?status=success&msg=" . urlencode("Subscription created successfully!"));
    } else {
        header("Location: index.php?status=error&msg=" . urlencode("Failed to create subscription."));
    }

    $stmt->close();
} else {
    echo "<script>alert('Please fill all required fields.'); window.history.back();</script>";
}

$conn->close();
