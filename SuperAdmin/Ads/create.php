<?php
include "../../database/db_connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad_type   = $_POST['ad_type'] ?? '';
    $gym_id    = $_POST['gym_id'] ?? null;
    $ads_name  = $_POST['ads_name'] ?? '';
    $title     = $_POST['title'] ?? '';
    $link_url  = $_POST['link_url'] ?? '';
    $status    = $_POST['status'] ?? '';
    $start_date = $_POST['start_date'] ?? '';

    if (!$ad_type || !$ads_name || !$title || !$status || !$start_date) {
        die("❌ Please fill all required fields.");
    }

    // Fetch ad plan details
    $planQuery = mysqli_query($conn, "SELECT name, duration_days FROM ad_plans WHERE plan_id='" . (int)$ads_name . "' LIMIT 1");
    if ($planQuery && mysqli_num_rows($planQuery) > 0) {
        $plan = mysqli_fetch_assoc($planQuery);
        $duration = (int)$plan['duration_days'];
        $ads_name_text = $plan['name'];
        $end_date = date('Y-m-d', strtotime("+$duration days", strtotime($start_date)));
    } else {
        die("❌ Selected ad plan not found.");
    }

    // ✅ Store actual image as BLOB (no compression)
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === 0) {
        $fileTmp = $_FILES['image_url']['tmp_name'];
        $fileSize = $_FILES['image_url']['size'];
        $maxSize = 5 * 1024 * 1024; // optional: 5MB max size

        if ($fileSize > $maxSize) {
            die("❌ Image size exceeds 5MB limit.");
        }

        // Read image content directly
        $imageData = file_get_contents($fileTmp);
        if ($imageData === false) {
            die("❌ Failed to read image file.");
        }

        // Escape binary for DB
        $imageDataEscaped = mysqli_real_escape_string($conn, $imageData);
    } else {
        die("❌ Please upload an image.");
    }

    // ✅ Insert into DB
    $sql = "INSERT INTO ads (ad_type, gym_id, title, image_url, link_url, ads_name, start_date, end_date, status)
            VALUES (
                '" . mysqli_real_escape_string($conn, $ad_type) . "',
                " . ($gym_id ? "'" . mysqli_real_escape_string($conn, $gym_id) . "'" : "NULL") . ",
                '" . mysqli_real_escape_string($conn, $title) . "',
                '$imageDataEscaped',
                '" . mysqli_real_escape_string($conn, $link_url) . "',
                '" . mysqli_real_escape_string($conn, $ads_name_text) . "',
                '" . mysqli_real_escape_string($conn, $start_date) . "',
                '" . mysqli_real_escape_string($conn, $end_date) . "',
                '" . mysqli_real_escape_string($conn, $status) . "'
            )";

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?status=success&msg=" . urlencode("✅ Ad added successfully!"));
        exit;
    } else {
        die("❌ Database error: " . mysqli_error($conn));
    }
}
?>

<?php
// Fetch all ad plans
$plans_result = mysqli_query($conn, "SELECT plan_id, name, duration_days FROM ad_plans WHERE status='active'");
$plans = [];
while ($row = mysqli_fetch_assoc($plans_result)) {
    $plans[] = $row;
}
?>

<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Create Advertisement</h4>
            </div>
            <div class="card-body">
                <form action="create.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>

                    <!-- Ad Type -->
                    <div class="mb-3">
                        <label for="ad_type" class="form-label">Ad Type</label>
                        <select class="form-select" id="ad_type" name="ad_type" required onchange="toggleGymId()">
                            <option value="" disabled selected>-- Select Type --</option>
                            <option value="gym">Gym</option>
                            <option value="partner">Partner</option>
                        </select>
                        <div class="invalid-feedback">Please select an ad type.</div>
                    </div>

                    <!-- Gym ID (conditional) -->
                    <div class="mb-3" id="gym_id_div" style="display:none;">
                        <label for="gym_id" class="form-label">Gym ID</label>
                        <input type="text" class="form-control" id="gym_id" name="gym_id">
                        <div class="invalid-feedback">Please enter Gym ID.</div>
                    </div>

                    <!-- Ads Name -->
                    <div class="mb-3">
                        <label for="ads_name" class="form-label">Ads Name</label>
                        <select class="form-select" id="ads_name" name="ads_name" required onchange="setDates()">
                            <option value="" disabled selected>-- Select Ad Plan --</option>
                            <?php foreach ($plans as $plan): ?>
                                <option value="<?= $plan['plan_id'] ?>" data-duration="<?= $plan['duration_days'] ?>">
                                    <?= htmlspecialchars($plan['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Please select an ad plan.</div>
                    </div>


                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                        <div class="invalid-feedback">Please enter the title.</div>
                    </div>

                    <!-- Image Upload -->
                    <div class="mb-3">
                        <label for="image_url" class="form-label">Upload Image</label>
                        <input class="form-control" type="file" id="image_url" name="image_url" accept="image/*" required>
                        <div class="invalid-feedback">Please upload an image.</div>
                    </div>

                    <!-- Link URL -->
                    <div class="mb-3">
                        <label for="link_url" class="form-label">Link URL</label>
                        <input type="url" class="form-control" id="link_url" name="link_url">
                        <div class="invalid-feedback">Please enter a valid URL.</div>
                    </div>

                    <!-- Start & End Date -->
                    <div class="row mb-3">
                        <div class="col">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                            <div class="invalid-feedback">Please select a start date.</div>
                        </div>
                        <div class="col">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                            <div class="invalid-feedback">Please select an end date.</div>
                        </div>
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
                        <button type="submit" class="btn-our px-5 py-2">Submit</button>
                    </div>

                </form>
            </div>
        </div>
    </main>
    <?php require("../assets/link.php"); ?>

    <script>
        // Bootstrap form validation
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

        // Show/hide Gym ID based on ad_type
        function toggleGymId() {
            const adType = document.getElementById('ad_type').value;
            const gymDiv = document.getElementById('gym_id_div');
            const gymInput = document.getElementById('gym_id');
            if (adType === 'gym') {
                gymDiv.style.display = 'block';
                gymInput.required = true;
            } else {
                gymDiv.style.display = 'none';
                gymInput.required = false;
            }
        }

        function setDates() {
            const select = document.getElementById('ads_name');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            const selectedOption = select.options[select.selectedIndex];
            if (!selectedOption || !selectedOption.dataset.duration) return;

            const duration = parseInt(selectedOption.dataset.duration);

            // Set start date as today
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            const startDateStr = `${yyyy}-${mm}-${dd}`;
            startDateInput.value = startDateStr;

            // Calculate end date based on duration
            const endDate = new Date(today);
            endDate.setDate(endDate.getDate() + duration);
            const endY = endDate.getFullYear();
            const endM = String(endDate.getMonth() + 1).padStart(2, '0');
            const endD = String(endDate.getDate()).padStart(2, '0');
            endDateInput.value = `${endY}-${endM}-${endD}`;
        }
    </script>