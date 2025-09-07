<?php
include "../../database/db_connect.php";

// Check if ID is passed
if (!isset($_GET['id'])) {
    die("❌ No ad ID provided.");
}

$ad_id = intval($_GET['id']);

// First, fetch the ad to delete image file as well
$query = "SELECT image_url FROM ads WHERE ad_id = $ad_id LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $ad = mysqli_fetch_assoc($result);

    // Delete the ad from DB
    $deleteQuery = "DELETE FROM ads WHERE ad_id = $ad_id";
    if (mysqli_query($conn, $deleteQuery)) {
        // If image exists on server, delete the file too
        $filePath = "../../" . $ad['image_url'];
        if (!empty($ad['image_url']) && file_exists($filePath)) {
            unlink($filePath);
        }

        header("Location: index.php?status=success&msg=" . urlencode("Ad deleted successfully."));
        exit;
    } else {
        die("❌ Error deleting ad: " . mysqli_error($conn));
    }
} else {
    die("❌ Ad not found.");
}
