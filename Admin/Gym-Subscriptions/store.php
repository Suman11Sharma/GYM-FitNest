<?php
include "../../database/db_connect.php";
session_start();

$gym_id = $_POST['gym_id'] ?? null;
$plan_id = intval($_POST['plan_id'] ?? 0);
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';
$amount = floatval($_POST['amount'] ?? 0);
$transaction_id = $_POST['transaction_id'] ?? '';

if (!$gym_id || $plan_id <= 0 || !$start_date || $amount <= 0 || !$transaction_id) {
    die("⚠️ Missing required data.");
}

// Fetch plan name and duration from DB
$stmt = $conn->prepare("SELECT plan_name, duration_months FROM saas_plans WHERE plan_id=? LIMIT 1");
$stmt->bind_param("i", $plan_id);
$stmt->execute();
$plan = $stmt->get_result()->fetch_assoc();
$stmt->close();

$plan_name = $plan['plan_name'] ?? 'Unknown Plan';
$duration_months = intval($plan['duration_months'] ?? 0);

// Calculate end date server-side
$start_dt = DateTime::createFromFormat('Y-m-d', $start_date);
$end_dt = clone $start_dt;
$end_dt->modify("+{$duration_months} months");
$end_date = $end_dt->format('Y-m-d');

// Insert subscription with pending payment
$created_at = $updated_at = date('Y-m-d H:i:s');
$payment_status = 'pending';
$status = 'inactive';

$insert = $conn->prepare("INSERT INTO gym_subscriptions
    (gym_id, plan_name, start_date, end_date, amount, payment_status, transaction_id, status, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$insert->bind_param(
    "issdssssis",
    $gym_id,
    $plan_name,
    $start_date,
    $end_date,
    $amount,
    $payment_status,
    $transaction_id,
    $status,
    $created_at,
    $updated_at
);

if ($insert->execute()) {
    $insert->close();

    // Redirect to eSewa process page
    $query = http_build_query([
        'amount' => $amount,
        'transaction_id' => $transaction_id,
        'plan_name' => $plan_name,
        'gym_id' => $gym_id
    ]);
    header("Location: esewa_process.php?$query");
    exit;
} else {
    die("❌ Failed to create subscription: " . $insert->error);
}
