<?php
include "../../database/db_connect.php";

$user_id = intval($_GET['user_id']);
$plan_id = intval($_GET['plan_id']);

$lastEnd = null;
$result = mysqli_query($conn, "SELECT end_date FROM customer_subscriptions WHERE user_id = $user_id AND plan_id = $plan_id ORDER BY end_date DESC LIMIT 1");
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $lastEnd = $row['end_date'];
}

header('Content-Type: application/json');
echo json_encode(['end_date' => $lastEnd]);
