<?php
include("../../database/db_connect.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?status=error&msg=" . urlencode("Invalid video ID."));
    exit;
}

$video_id = intval($_GET['id']);

// Fetch current video details
$stmt = $conn->prepare("SELECT * FROM videos WHERE video_id = ?");
$stmt->bind_param("i", $video_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header("Location: index.php?status=error&msg=" . urlencode("Video not found."));
    exit;
}
$video = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];

    // File upload logic
    $filename = $video['filename']; // Keep old filename by default
    if (!empty($_FILES['video_file']['name'])) {
        $targetDir = "../../uploads/videos/";
        $newFileName = time() . "_" . basename($_FILES['video_file']['name']);
        $targetFile = $targetDir . $newFileName;

        if (move_uploaded_file($_FILES['video_file']['tmp_name'], $targetFile)) {
            // Delete old file if exists
            $oldFile = $targetDir . $video['filename'];
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
            $filename = $newFileName; // Use new filename
        } else {
            header("Location: edit.php?id=$video_id&status=error&msg=" . urlencode("Video upload failed."));
            exit;
        }
    }

    // Update DB record
    $update = $conn->prepare("UPDATE videos SET title=?, description=?, filename=?, status=? WHERE video_id=?");
    $update->bind_param("ssssi", $title, $description, $filename, $status, $video_id);

    if ($update->execute()) {
        header("Location: index.php?status=success&msg=" . urlencode("Video updated successfully."));
        exit;
    } else {
        header("Location: edit.php?id=$video_id&status=error&msg=" . urlencode("Failed to update video."));
        exit;
    }
}
require("../sidelayout.php");
?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Edit Video</h4>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>

                    <!-- Title -->
                    <div class="mb-3">
                        <label class="form-label">Video Title</label>
                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($video['title']); ?>" required>
                        <div class="invalid-feedback">Please enter a title.</div>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($video['description']); ?></textarea>
                    </div>

                    <!-- Current Video -->
                    <div class="mb-3">
                        <label class="form-label">Current Video</label><br>
                        <video width="300" controls>
                            <source src="../../uploads/videos/<?php echo htmlspecialchars($video['filename']); ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>

                    <!-- Replace Video -->
                    <div class="mb-3">
                        <label class="form-label">Replace Video (optional)</label>
                        <input type="file" name="video_file" class="form-control" accept="video/*">
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active" <?php echo ($video['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo ($video['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                        <div class="invalid-feedback">Please select a status.</div>
                    </div>

                    <!-- Submit -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-4 py-2">Update Video</button>
                        <a href="index.php" class="btn btn-secondary px-4 py-2 ms-2">Cancel</a>
                    </div>

                </form>
            </div>
        </div>
    </main>
</div>

<?php require("../assets/link.php"); ?>

<script>
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>