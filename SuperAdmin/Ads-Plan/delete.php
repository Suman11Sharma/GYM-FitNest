<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

if (isset($_GET['id'])) {
    $planId = intval($_GET['id']);

    // Delete query
    $sql = "DELETE FROM ad_plans WHERE plan_id = $planId";

    if (mysqli_query($conn, $sql)) {
        $status = "success";
        $msg = "Ads plan deleted successfully!";
    } else {
        $status = "error";
        $msg = "Failed to delete ad plan: " . mysqli_error($conn);
    }

    // Redirect back to index with feedback
    header("Location: index.php?status=$status&msg=" . urlencode($msg));
    exit;
} else {
    // No ID provided
    header("Location: index.php?status=error&msg=" . urlencode("Invalid request."));
    exit;
}
