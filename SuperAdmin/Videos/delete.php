<?php
include("../../database/db_connect.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?status=error&msg=" . urlencode("Invalid video ID."));
    exit;
}

$video_id = intval($_GET['id']);

// Fetch video to get filename
$stmt = $conn->prepare("SELECT filename FROM videos WHERE video_id = ?");
$stmt->bind_param("i", $video_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php?status=error&msg=" . urlencode("Video not found."));
    exit;
}

$video = $result->fetch_assoc();
$videoPath = "../../uploads/videos/" . $video['filename'];

// Delete file if it exists
if (file_exists($videoPath)) {
    unlink($videoPath);
}

// Delete record from database
$delete = $conn->prepare("DELETE FROM videos WHERE video_id = ?");
$delete->bind_param("i", $video_id);

if ($delete->execute()) {
    header("Location: index.php?status=success&msg=" . urlencode("Video deleted successfully."));
    exit;
} else {
    header("Location: index.php?status=error&msg=" . urlencode("Failed to delete video."));
    exit;
}
