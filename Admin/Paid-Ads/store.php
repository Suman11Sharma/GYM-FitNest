<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $gym_id = $_SESSION['gym_id'] ?? 0;
    $plan_id = intval($_POST['ads_plan']); // plan_id from <select>
    $link_url = trim($_POST['link_url']);
    $amount = floatval($_POST['amount']);
    $transaction_id = "TXN_" . uniqid();

    $payment_status = "Pending";
    $status = "Inactive";
    $approval_status = "approved";
    $created_at = $updated_at = date('Y-m-d H:i:s');
    $start_date = date('Y-m-d');

    // ✅ Fetch plan details
    $plan_query = $conn->prepare("SELECT name, duration_days, price FROM ad_plans WHERE plan_id = ?");
    $plan_query->bind_param("i", $plan_id);
    $plan_query->execute();
    $plan_query->bind_result($plan_name, $duration_days, $plan_price);
    $plan_query->fetch();
    $plan_query->close();

    if (!$plan_name) {
        die("❌ Invalid ad plan selected.");
    }

    if (!$duration_days) $duration_days = 7;
    $end_date = date('Y-m-d', strtotime("+$duration_days days"));

    // ✅ Image upload
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $image_tmp = $_FILES['image_file']['tmp_name'];
        $image_data = file_get_contents($image_tmp);
    } else {
        die("❌ Please upload a valid image file.");
    }

    // ✅ Insert data — fix parameter types (use 's' for plan_name)
    $sql = "INSERT INTO paid_ads 
            (gym_id, ads_plan, image_url, link_url, start_date, end_date, amount, payment_status, transaction_id, approval_status, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "isssssdssssss",  // changed: second param is now 's' for string plan_name
        $gym_id,
        $plan_name,        // ✅ store plan name here
        $image_data,
        $link_url,
        $start_date,
        $end_date,
        $amount,
        $payment_status,
        $transaction_id,
        $approval_status,
        $status,
        $created_at,
        $updated_at
    );
    $stmt->send_long_data(2, $image_data);

    if ($stmt->execute()) {
        // Redirect to payment process
        header("Location: process_esewa.php?transaction_id={$transaction_id}&amount={$amount}&gym_id={$gym_id}");
        exit();
    } else {
        die("❌ Database insert failed: " . $stmt->error);
    }

    $stmt->close();
}
