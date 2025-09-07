<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Create Advertisement</h4>
            </div>
            <div class="card-body">
                <form action="store.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>

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
                        <input type="text" class="form-control" id="ads_name" name="ads_name" required>
                        <div class="invalid-feedback">Please enter the ad name.</div>
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
    </script>