<?php
include "../../database/db_connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $status = $_POST['status'] ?? 'inactive';

    // Validate required fields
    if (empty($title)) {
        die("❌ Please enter the video title.");
    }

    // Handle video upload
    if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] === 0) {
        $targetDir = "../uploads/videos/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileExt = strtolower(pathinfo($_FILES['video_file']['name'], PATHINFO_EXTENSION));
        $allowedExt = ['mp4', 'webm', 'ogg'];

        if (!in_array($fileExt, $allowedExt)) {
            die("❌ Invalid file type. Only MP4, WEBM, OGG allowed.");
        }

        $fileName = time() . "_" . basename($_FILES["video_file"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        if (!move_uploaded_file($_FILES["video_file"]["tmp_name"], $targetFilePath)) {
            die("❌ Error uploading video.");
        }

        $dbFilePath = "uploads/videos/" . $fileName;
    } else {
        die("❌ Please upload a video file.");
    }

    // Insert into videos table
    $sql = "INSERT INTO videos (title, description, filename, status)
            VALUES (
                '" . mysqli_real_escape_string($conn, $title) . "',
                '" . mysqli_real_escape_string($conn, $description) . "',
                '" . mysqli_real_escape_string($conn, $dbFilePath) . "',
                '" . mysqli_real_escape_string($conn, $status) . "'
            )";

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?status=success&msg=" . urlencode("Video added successfully!"));
        exit;
    } else {
        header("Location: index.php?status=error&msg=" . urlencode("Database error: " . mysqli_error($conn)));
        exit;
    }
}
?>

<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Upload New Video</h4>
            </div>
            <div class="card-body">
                <form action="create.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>

                    <!-- Video Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Video Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                        <div class="invalid-feedback">Please enter a video title.</div>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <!-- Video Upload -->
                    <div class="mb-3">
                        <label for="video_file" class="form-label">Upload Video</label>
                        <input class="form-control" type="file" id="video_file" name="video_file" accept="video/*" required>
                        <div class="invalid-feedback">Please upload a video file.</div>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="" disabled selected>-- Select Status --</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <div class="invalid-feedback">Please select a status.</div>
                    </div>

                    <!-- Submit -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-5 py-2">Upload</button>
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