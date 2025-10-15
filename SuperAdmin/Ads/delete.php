<?php
include "../../database/db_connect.php";

if (!isset($_GET['id'])) {
    die("❌ No ad ID provided.");
}

$ad_id = intval($_GET['id']);

// First, check if the ad exists
$checkQuery = "SELECT ad_id FROM ads WHERE ad_id = $ad_id LIMIT 1";
$checkResult = mysqli_query($conn, $checkQuery);

if ($checkResult && mysqli_num_rows($checkResult) > 0) {

    // Delete from database (image will be deleted automatically since it's stored in the same row)
    $deleteQuery = "DELETE FROM ads WHERE ad_id = $ad_id";
    if (mysqli_query($conn, $deleteQuery)) {
        header("Location: index.php?status=success&msg=" . urlencode("Ad deleted successfully."));
        exit;
    } else {
        die("❌ Error deleting ad: " . mysqli_error($conn));
    }
} else {
    die("❌ Ad not found.");
}
