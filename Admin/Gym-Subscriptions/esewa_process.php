<?php
session_start();

// ✅ Get data from GET (redirect from store.php)
$gym_id = $_GET['gym_id'] ?? '';
$amount = floatval($_GET['amount'] ?? 0);
$transaction_id = $_GET['transaction_id'] ?? '';
$plan_name = $_GET['plan_name'] ?? '';

if (!$gym_id || $amount <= 0 || !$transaction_id || !$plan_name) {
    die("⚠️ Missing required data.");
}

// eSewa config
$product_code = "EPAYTEST";
$secret = "8gBm/:&EnhH.1/q";

$total_amount = $amount;
$tax_amount = 0;
$service_charge = 0;
$delivery_charge = 0;

// Redirect URLs
$success_url = "http://localhost/GYM-FitNest/Admin/Gym-Subscriptions/success_esewa.php?transaction_id={$transaction_id}";
$failure_url = "http://localhost/GYM-FitNest/Admin/Gym-Subscriptions/fail_esewa.php";

// Signature for security
$message = "total_amount={$total_amount},transaction_uuid={$transaction_id},product_code={$product_code}";
$signature = base64_encode(hash_hmac('sha256', $message, $secret, true));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Redirecting to eSewa...</title>
    <style>
        body {
            font-family: Arial;
            text-align: center;
            margin-top: 100px;
        }

        .loader {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #4CAF50;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body onload="document.getElementById('esewaForm').submit()">
    <h3>Redirecting to eSewa for secure payment...</h3>
    <div class="loader"></div>
    <p>Please wait...</p>

    <form id="esewaForm" action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
        <input type="hidden" name="amount" value="<?= htmlspecialchars($amount) ?>">
        <input type="hidden" name="tax_amount" value="<?= htmlspecialchars($tax_amount) ?>">
        <input type="hidden" name="total_amount" value="<?= htmlspecialchars($total_amount) ?>">
        <input type="hidden" name="transaction_uuid" value="<?= htmlspecialchars($transaction_id) ?>">
        <input type="hidden" name="product_code" value="<?= htmlspecialchars($product_code) ?>">
        <input type="hidden" name="product_service_charge" value="<?= htmlspecialchars($service_charge) ?>">
        <input type="hidden" name="product_delivery_charge" value="<?= htmlspecialchars($delivery_charge) ?>">
        <input type="hidden" name="success_url" value="<?= htmlspecialchars($success_url) ?>">
        <input type="hidden" name="failure_url" value="<?= htmlspecialchars($failure_url) ?>">
        <input type="hidden" name="signed_field_names" value="total_amount,transaction_uuid,product_code">
        <input type="hidden" name="signature" value="<?= htmlspecialchars($signature) ?>">
    </form>
</body>

</html>