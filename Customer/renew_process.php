<?php
include "../database/user_authentication.php";
include "../database/db_connect.php";

if (!isset($_SESSION['customer_id']) || !isset($_SESSION['gym_id'])) {
    die("⚠️ Session expired. Please log in again.");
}

$customer_id = $_SESSION['customer_id'];
$gym_id = $_SESSION['gym_id'];
$plan_id = $_POST['plan_id'] ?? '';
$amount = $_POST['amount'] ?? '';

if (empty($plan_id) || empty($amount)) {
    die("⚠️ Missing required information.");
}

// Get plan duration
$plan_query = "SELECT duration_days FROM customer_plans WHERE plan_id=? AND gym_id=?";
$stmt = $conn->prepare($plan_query);
$stmt->bind_param("ii", $plan_id, $gym_id);
$stmt->execute();
$plan_result = $stmt->get_result();

if ($plan_result->num_rows == 0) {
    die("⚠️ Invalid plan selected.");
}
$plan = $plan_result->fetch_assoc();
$duration_days = (int)$plan['duration_days'];

// Check last subscription (if exists)
$sub_query = "SELECT end_date FROM customer_subscriptions WHERE user_id=? AND gym_id=? AND status='active' ORDER BY end_date DESC LIMIT 1";
$stmt = $conn->prepare($sub_query);
$stmt->bind_param("ii", $customer_id, $gym_id);
$stmt->execute();
$sub_result = $stmt->get_result();

if ($sub_result->num_rows > 0) {
    $last_sub = $sub_result->fetch_assoc();
    $start_date = (strtotime($last_sub['end_date']) > time()) ? $last_sub['end_date'] : date('Y-m-d');
} else {
    // No previous subscription
    $start_date = date('Y-m-d');
}

// Calculate end date
$end_date = date('Y-m-d', strtotime("$start_date +$duration_days days"));

// Generate transaction ID
$transaction_id = uniqid("txn_");

// Insert into customer_subscriptions (status inactive, payment pending)
$insert_query = "INSERT INTO customer_subscriptions 
    (user_id, plan_id, gym_id, start_date, end_date, amount, payment_status, transaction_id, status, created_at) 
    VALUES (?, ?, ?, ?, ?, ?, 'pending', ?, 'inactive', NOW())";

$stmt = $conn->prepare($insert_query);
$stmt->bind_param("iiissss", $customer_id, $plan_id, $gym_id, $start_date, $end_date, $amount, $transaction_id);

if ($stmt->execute()) {
    // Redirect to eSewa for payment
    $esewa_url = "https://rc-epay.esewa.com.np/api/epay/main/v2/form";
    $success_url = "http://localhost/GYM-FitNest/Customer/success_esewa.php?transaction_id={$transaction_id}";
    $failure_url = "http://localhost/GYM-FitNest/Customer/fail_esewa.php";

    $product_code = "EPAYTEST";
    $secret = "8gBm/:&EnhH.1/q";
    $total_amount = $amount;
    $message = "total_amount={$total_amount},transaction_uuid={$transaction_id},product_code={$product_code}";
    $signature = base64_encode(hash_hmac('sha256', $message, $secret, true));

    echo "
    <form id='esewaForm' action='{$esewa_url}' method='POST'>
        <input type='hidden' name='amount' value='{$amount}'>
        <input type='hidden' name='tax_amount' value='0'>
        <input type='hidden' name='total_amount' value='{$amount}'>
        <input type='hidden' name='transaction_uuid' value='{$transaction_id}'>
        <input type='hidden' name='product_code' value='{$product_code}'>
        <input type='hidden' name='product_service_charge' value='0'>
        <input type='hidden' name='product_delivery_charge' value='0'>
        <input type='hidden' name='success_url' value='{$success_url}'>
        <input type='hidden' name='failure_url' value='{$failure_url}'>
        <input type='hidden' name='signed_field_names' value='total_amount,transaction_uuid,product_code'>
        <input type='hidden' name='signature' value='{$signature}'>
    </form>

    <script>
        document.getElementById('esewaForm').submit();
    </script>
    ";
} else {
    die("⚠️ Failed to create renewal record: " . $stmt->error);
}
