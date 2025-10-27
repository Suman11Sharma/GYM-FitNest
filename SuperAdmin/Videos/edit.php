<?php
require("../sidelayout.php");
include "../../database/db_connect.php";

// 1. Get video ID
if (!isset($_GET['id'])) {
    echo "No video ID provided.";
    exit;
}
$video_id = intval($_GET['id']);

// 2. Fetch video info
$query = "SELECT * FROM videos WHERE id = $video_id LIMIT 1";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    echo "Video not found.";
    exit;
}
$video = mysqli_fetch_assoc($result);
?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Edit Video</h4>
                <a href="index.php" class="btn btn-light btn-sm border ms-3" title="Back to Videos Table">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <div class="card-body">
                <form action="update.php?id=<?= $video_id ?>" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Video Title</label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="<?= htmlspecialchars($video['title']) ?>" required>
                        <div class="invalid-feedback">Please enter a title.</div>
                    </div>

                    <!-- Video Upload -->
                    <div class="mb-3">
                        <label for="video_file" class="form-label">Upload Video</label>
                        <input class="form-control" type="file" id="video_file" name="video_file" accept="video/*">

                        <?php if (!empty($video['filename'])): ?>
                            <div class="mt-2">
                                <video width="400" height="250" controls>
                                    <source src="/SuperAdmin/uploads/videos/<?= htmlspecialchars($video['filename']) ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                                <input type="hidden" name="existing_file" value="<?= htmlspecialchars($video['filename']) ?>">
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="" disabled>-- Select Status --</option>
                            <option value="active" <?= $video['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $video['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                        <div class="invalid-feedback">Please select a status.</div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn-our px-5 py-2">Update</button>
                    </div>

                </form>
            </div>
        </div>
    </main>

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
</div>