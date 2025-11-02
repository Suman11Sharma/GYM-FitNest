<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// --- Check if form submitted ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $payment_status = mysqli_real_escape_string($conn, $_POST['payment_status']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // --- Optional: Prevent duplicate active subscriptions ---
    if ($status === 'active') {
        $check = "SELECT * FROM customer_subscriptions WHERE user_id = 
                  (SELECT user_id FROM customer_subscriptions WHERE subscription_id = $id)
                  AND status = 'active' AND subscription_id != $id";
        $checkResult = mysqli_query($conn, $check);
        if ($checkResult && mysqli_num_rows($checkResult) > 0) {
            $_SESSION['error'] = "User already has an active subscription!";
            header("Location: edit.php?id=$id");
            exit;
        }
    }

    // --- Update query ---
    $updateQuery = "UPDATE customer_subscriptions 
                    SET payment_status = '$payment_status', status = '$status', updated_at = NOW()
                    WHERE subscription_id = $id";

    if (mysqli_query($conn, $updateQuery)) {
        $_SESSION['success'] = "Subscription updated successfully!";
        header("Location: index.php?status=success&msg=" . urlencode("Subscription updated successfully!"));
        exit;
    } else {
        $_SESSION['error'] = "Error updating subscription: " . mysqli_error($conn);
        header("Location: index.php?status=success&msg=" . urlencode("Error updating subscription: " . mysqli_error($conn)));
        exit;
    }
} else {
    // If not POST, redirect back
    header("Location: index.php");
    exit;
}
