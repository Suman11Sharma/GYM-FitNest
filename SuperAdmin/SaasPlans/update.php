<?php
include "../../database/db_connect.php";

// Check if POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize input
    $plan_id         = intval($_POST['plan_id']);
    $plan_name       = trim($_POST['plan_name']);
    $features        = trim($_POST['features']);
    $amount          = floatval($_POST['amount']);
    $duration_months = intval($_POST['duration_months']);
    $status          = trim($_POST['status']);

    if (empty($plan_name) || empty($features) || $amount < 0 || $duration_months < 1 || empty($status)) {
        header("Location: index.php?status=error&msg=Invalid input values");
        exit();
    }

    // Prepare update query
    $sql = "UPDATE saas_plans 
            SET plan_name = ?, features = ?, amount = ?, duration_months = ?, status = ? 
            WHERE plan_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        header("Location: index.php?status=error&msg=Failed to prepare statement");
        exit();
    }

    $stmt->bind_param("ssdisi", $plan_name, $features, $amount, $duration_months, $status, $plan_id);

    if ($stmt->execute()) {
        header("Location: index.php?status=success&msg=Plan updated successfully");
    } else {
        header("Location: index.php?status=error&msg=Failed to update plan");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php?status=error&msg=Invalid request method");
    exit();
}
