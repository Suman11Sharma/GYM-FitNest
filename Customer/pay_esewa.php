<?php
include "../database/user_authentication.php";
include "../database/db_connect.php";

$gym_id = $_GET['gym_id'] ?? '';
$amount = floatval($_GET['amount'] ?? 0);
$transaction_id = $_GET['transaction_id'] ?? '';

if (empty($transaction_id) || $amount <= 0) {
    die("⚠️ Missing required data.");
}

$product_code = "EPAYTEST";
$secret = "8gBm/:&EnhH.1/q";

$total_amount = $amount;
$success_url = "http://localhost/GYM-FitNest/Customer/success_esewa.php?transaction_id={$transaction_id}";
$failure_url = "http://localhost/GYM-FitNest/Customer//fail_esewa.php";

$message = "total_amount={$total_amount},transaction_uuid={$transaction_id},product_code={$product_code}";
$signature = base64_encode(hash_hmac('sha256', $message, $secret, true));
?>
<!-- same HTML/loader as your previous working code -->