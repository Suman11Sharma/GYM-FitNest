<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $gym_id = intval($_POST['gym_id']);
    $plan_id = intval($_POST['plan_id']);
    $payment_status = mysqli_real_escape_string($conn, $_POST['payment_status']);
    $transaction_id = mysqli_real_escape_string($conn, $_POST['transaction_id']);

    // --- Fetch selected plan details ---
    $planQuery = "SELECT * FROM customer_plans WHERE plan_id = $plan_id LIMIT 1";
    $planResult = mysqli_query($conn, $planQuery);
    if (!$planResult || mysqli_num_rows($planResult) == 0) {
        $_SESSION['error'] = "Selected plan not found!";
        header("Location: renew.php?user_id=$user_id");
        exit;
    }
    $plan = mysqli_fetch_assoc($planResult);
    $duration = intval($plan['duration_days']);
    $amount = $plan['amount'];

    // --- Determine start date based on last subscription ---
    $lastQuery = "SELECT * FROM customer_subscriptions 
                  WHERE user_id = $user_id AND gym_id = $gym_id 
                  ORDER BY end_date DESC LIMIT 1";
    $lastResult = mysqli_query($conn, $lastQuery);
    $start_date = date('Y-m-d');
    if ($lastResult && mysqli_num_rows($lastResult) > 0) {
        $lastSub = mysqli_fetch_assoc($lastResult);
        if ($lastSub['end_date'] >= $start_date) {
            $start_date = date('Y-m-d', strtotime($lastSub['end_date'] . ' +1 day'));
        }
    }

    // --- Calculate end date ---
    $end_date = date('Y-m-d', strtotime($start_date . " +" . ($duration - 1) . " days"));

    // --- Insert new subscription ---
    $insertQuery = "INSERT INTO customer_subscriptions 
        (user_id, gym_id, plan_id, start_date, end_date, amount, payment_status, transaction_id, status, created_at, updated_at)
        VALUES
        ($user_id, $gym_id, $plan_id, '$start_date', '$end_date', $amount, '$payment_status', '$transaction_id', 'active', NOW(), NOW())";

    if (mysqli_query($conn, $insertQuery)) {
        $_SESSION['success'] = "Subscription renewed successfully!";
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
        header("Location: renew.php?user_id=$user_id");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
