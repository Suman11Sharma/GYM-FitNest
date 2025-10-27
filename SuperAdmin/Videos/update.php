<?php
include "../../database/db_connect.php";

if (!isset($_GET['id'])) {
    header("Location: index.php?status=error&msg=" . urlencode("No Video ID provided"));
    exit;
}

$video_id = intval($_GET['id']);

// Escape input values
$title = mysqli_real_escape_string($conn, $_POST['title']);
$status = mysqli_real_escape_string($conn, $_POST['status']);
$updated_at = date("Y-m-d H:i:s");

// Handle existing video file
$filename = $_POST['existing_file'] ?? "";

// Handle new video upload
if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = "../uploads/videos/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $newFile = time() . "_" . basename($_FILES['video_file']['name']);
    $targetFile = $uploadDir . $newFile;

    if (move_uploaded_file($_FILES['video_file']['tmp_name'], $targetFile)) {
        // Delete old video if exists
        if (!empty($filename) && file_exists($uploadDir . $filename)) {
            unlink($uploadDir . $filename);
        }
        $filename = $newFile;
    } else {
        header("Location: edit.php?id=$video_id&status=error&msg=" . urlencode("Video upload failed."));
        exit;
    }
}

// Update database
$update = "
    UPDATE videos SET
        title = '$title',
        filename = '$filename',
        status = '$status',
        updated_at = '$updated_at'
    WHERE id = $video_id
";

if (mysqli_query($conn, $update)) {
    header("Location: index.php?status=success&msg=" . urlencode("Video updated successfully"));
    exit;
} else {
    header("Location: edit.php?id=$video_id&status=error&msg=" . urlencode("Update failed: " . mysqli_error($conn)));
    exit;
}
