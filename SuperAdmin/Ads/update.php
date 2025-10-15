<?php
include "../../database/db_connect.php";

if (!isset($_GET['id'])) {
    header("Location: index.php?status=error&msg=" . urlencode("No Ad ID provided"));
    exit;
}

$ad_id = intval($_GET['id']);

// Escape values
$ad_type = mysqli_real_escape_string($conn, $_POST['ad_type']);
$ads_name = mysqli_real_escape_string($conn, $_POST['ads_name']);
$title = mysqli_real_escape_string($conn, $_POST['title']);
$link_url = mysqli_real_escape_string($conn, $_POST['link_url']);
$start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
$end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
$status = mysqli_real_escape_string($conn, $_POST['status']);
$updated_at = date("Y-m-d H:i:s");

// Handle gym_id separately (nullable)
$gym_id = isset($_POST['gym_id']) && $_POST['gym_id'] !== ''
    ? intval($_POST['gym_id'])
    : "NULL"; // <- if empty, store NULL

// Handle image upload
$image_url = $_POST['existing_image'] ?? "";
if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = "../uploads/ads_images/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $filename = time() . "_" . basename($_FILES['image_url']['name']);
    $targetFile = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['image_url']['tmp_name'], $targetFile)) {
        $image_url = "uploads/ads_images/" . $filename;
    }
}

// Build UPDATE SQL (note: no quotes around $gym_id if it's NULL)
$update = "
    UPDATE ads SET
        ad_type = '$ad_type',
        gym_id = $gym_id,
        ads_name = '$ads_name',
        title = '$title',
        image_url = '$image_url',
        link_url = '$link_url',
        start_date = '$start_date',
        end_date = '$end_date',
        status = '$status',
        updated_at = '$updated_at'
    WHERE ad_id = $ad_id
";

// Execute
if (mysqli_query($conn, $update)) {
    header("Location: index.php?status=success&msg=" . urlencode(" Ad updated successfully"));
    exit;
} else {
    header("Location: index.php?status=error&msg=" . urlencode(" Update failed: " . mysqli_error($conn)));
    exit;
}
