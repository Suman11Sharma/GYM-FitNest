<?php
include "../../database/db_connect.php";

if (!isset($_GET['id'])) {
    die("❌ No ad ID provided.");
}

$ad_id = intval($_GET['id']);

// Fetch the image path first
$query = "SELECT image_url FROM ads WHERE ad_id = $ad_id LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $ad = mysqli_fetch_assoc($result);
    $relativePath = $ad['image_url'];  // e.g. uploads/ads_images/image.jpg
    $fullPath = realpath(__DIR__ . "/../" . $relativePath);

    // Delete from database first
    $deleteQuery = "DELETE FROM ads WHERE ad_id = $ad_id";
    if (mysqli_query($conn, $deleteQuery)) {

        // Then delete the image file if it exists
        if (!empty($relativePath) && $fullPath && file_exists($fullPath)) {
            unlink($fullPath);
        }

        header("Location: index.php?status=success&msg=" . urlencode("Ad deleted successfully."));
        exit;
    } else {
        die("❌ Error deleting ad: " . mysqli_error($conn));
    }
} else {
    die("❌ Ad not found.");
}
