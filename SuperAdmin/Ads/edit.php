<?php
require("../sidelayout.php");
include "../../database/db_connect.php";

// Fetch ad ID
if (!isset($_GET['id'])) {
    echo "No ad ID provided.";
    exit;
}
$ad_id = intval($_GET['id']);

// Fetch ad data
$query = "SELECT * FROM ads WHERE ad_id = $ad_id LIMIT 1";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    echo "Ad not found.";
    exit;
}
$ad = mysqli_fetch_assoc($result);

// Fetch all ad plans for dropdown
$plans = [];
$planQuery = mysqli_query($conn, "SELECT plan_id, name FROM ad_plans WHERE status = 'active'");
if ($planQuery && mysqli_num_rows($planQuery) > 0) {
    while ($row = mysqli_fetch_assoc($planQuery)) {
        $plans[] = $row;
    }
}

// Convert blob to base64 (if available)
$existingImageSrc = '';
if (!empty($ad['image_url'])) {
    $base64 = base64_encode($ad['image_url']);
    // Assuming JPEG, but you can detect type if needed
    $existingImageSrc = "data:image/jpeg;base64," . $base64;
}
?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Edit Advertisement</h4>
                <a href="index.php" class="btn btn-light btn-sm border ms-3" title="Back to Ads Table">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <div class="card-body">
                <form action="update.php?id=<?= $ad_id ?>" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>

                    <!-- Ad Type -->
                    <div class="mb-3">
                        <label for="ad_type" class="form-label">Ad Type</label>
                        <select class="form-select" id="ad_type" name="ad_type" required onchange="toggleGymId()">
                            <option value="" disabled>-- Select Type --</option>
                            <option value="gym" <?= $ad['ad_type'] == 'gym' ? 'selected' : '' ?>>Gym</option>
                            <option value="partner" <?= $ad['ad_type'] == 'partner' ? 'selected' : '' ?>>Partner</option>
                        </select>
                        <div class="invalid-feedback">Please select an ad type.</div>
                    </div>

                    <!-- Gym ID -->
                    <div class="mb-3" id="gym_id_div" style="display: <?= $ad['ad_type'] == 'gym' ? 'block' : 'none' ?>;">
                        <label for="gym_id" class="form-label">Gym ID</label>
                        <input type="text" class="form-control" id="gym_id" name="gym_id"
                            value="<?= htmlspecialchars($ad['gym_id']) ?>" <?= $ad['ad_type'] == 'gym' ? 'required' : '' ?>>
                        <div class="invalid-feedback">Please enter Gym ID.</div>
                    </div>

                    <!-- Ads Name (dropdown from plans) -->
                    <div class="mb-3">
                        <label for="ads_name" class="form-label">Ads Name (Plan)</label>
                        <select class="form-select" id="ads_name" name="ads_name" required>
                            <option value="" disabled>-- Select Plan --</option>
                            <?php foreach ($plans as $plan): ?>
                                <option value="<?= htmlspecialchars($plan['name']) ?>"
                                    <?= $plan['name'] == $ad['ads_name'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($plan['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Please select an ad plan.</div>
                    </div>

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="<?= htmlspecialchars($ad['title']) ?>" required>
                        <div class="invalid-feedback">Please enter the title.</div>
                    </div>

                    <!-- Image Upload -->
                    <div class="mb-3">
                        <label for="image_url" class="form-label">Upload Image (Optional)</label>
                        <input class="form-control" type="file" id="image_url" name="image_url" accept="image/*">

                        <?php if (!empty($existingImageSrc)): ?>
                            <div class="mt-2">
                                <img src="<?= $existingImageSrc ?>"
                                    alt="Ad Image"
                                    width="400" height="200"
                                    class="border rounded shadow-sm"
                                    style="object-fit: cover;">
                                <input type="hidden" name="existing_image" value="1">
                            </div>
                        <?php endif; ?>

                        <div class="form-text">Leave empty to keep existing image.</div>
                    </div>

                    <!-- Link URL -->
                    <div class="mb-3">
                        <label for="link_url" class="form-label">Link URL</label>
                        <input type="url" class="form-control" id="link_url" name="link_url"
                            value="<?= htmlspecialchars($ad['link_url']) ?>">
                        <div class="invalid-feedback">Please enter a valid URL.</div>
                    </div>

                    <!-- Dates -->
                    <div class="row mb-3">
                        <div class="col">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date"
                                value="<?= $ad['start_date'] ?>" required>
                            <div class="invalid-feedback">Please select a start date.</div>
                        </div>
                        <div class="col">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date"
                                value="<?= $ad['end_date'] ?>" required>
                            <div class="invalid-feedback">Please select an end date.</div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="" disabled>-- Select Status --</option>
                            <option value="active" <?= $ad['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $ad['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
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
    </script>
</div>