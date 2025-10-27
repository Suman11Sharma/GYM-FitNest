<?php
include "../../database/db_connect.php";

if (!isset($_GET['id'])) {
    die("❌ No video ID provided.");
}

$video_id = intval($_GET['id']);

// Fetch the video file path first
$query = "SELECT filename FROM videos WHERE id = $video_id LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $video = mysqli_fetch_assoc($result);
    $relativePath = 'SuperAdmin/uploads/videos/' . $video['filename'];
    $fullPath = realpath(__DIR__ . "/../" . $relativePath);

    // Delete from database first
    $deleteQuery = "DELETE FROM videos WHERE id = $video_id";
    if (mysqli_query($conn, $deleteQuery)) {

        // Then delete the video file if it exists
        if (!empty($relativePath) && $fullPath && file_exists($fullPath)) {
            unlink($fullPath);
        }

        header("Location: index.php?status=success&msg=" . urlencode("Video deleted successfully."));
        exit;
    } else {
        die("❌ Error deleting video: " . mysqli_error($conn));
    }
} else {
    die("❌ Video not found.");
}
