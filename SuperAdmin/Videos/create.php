<?php
session_start();
include("../../database/db_connect.php"); // adjust path as needed

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status = trim($_POST['status']);

    // ✅ Check if file uploaded
    if (!isset($_FILES['video_file']) || $_FILES['video_file']['error'] !== 0) {
        header("Location: index.php?status=error&msg=" . urlencode("Please select a valid video file."));
        exit();
    }

    // ✅ Define upload directory
    $upload_dir = "../../uploads/videos/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // create folder if not exists
    }

    // ✅ File handling
    $original_name = basename($_FILES['video_file']['name']);
    $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
    $allowed = ['mp4', 'mov', 'avi', 'mkv'];

    if (!in_array($ext, $allowed)) {
        header("Location: index.php?status=error&msg=" . urlencode("Only MP4, MOV, AVI, MKV files allowed."));
        exit();
    }

    // ✅ Generate unique filename to avoid overwriting
    $filename = time() . "_" . preg_replace("/[^a-zA-Z0-9_\.-]/", "_", $original_name);
    $target_path = $upload_dir . $filename;

    // ✅ Move file to upload folder
    if (!move_uploaded_file($_FILES['video_file']['tmp_name'], $target_path)) {
        header("Location: index.php?status=error&msg=" . urlencode("Failed to upload video."));
        exit();
    }

    // ✅ Insert video details into database
    $stmt = $conn->prepare("INSERT INTO videos (title, filename, description, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $filename, $description, $status);

    if ($stmt->execute()) {
        header("Location: index.php?status=success&msg=" . urlencode("Video uploaded successfully!"));
        exit();
    } else {
        header("Location: index.php?status=error&msg=" . urlencode("Database error: " . $stmt->error));
        exit();
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