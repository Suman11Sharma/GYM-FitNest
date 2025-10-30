<?php
include "../../database/db_connect.php";
session_start();

// ✅ Get logged-in gym owner ID from session
$gym_id = $_SESSION['gym_id'] ?? null;
if (!$gym_id) {
    die("⚠️ Gym ID not found in session. Please log in again.");
}

// ✅ Fetch active ad plans (only active)
$plans_query = $conn->query("SELECT plan_id, name, price FROM ad_plans WHERE status='active'");
$plans = [];
if ($plans_query && $plans_query->num_rows > 0) {
    $plans = $plans_query->fetch_all(MYSQLI_ASSOC);
}
?>
<?php
require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Paid Ads</h4>
            </div>

            <div class="card-body">
                <form action="store.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>

                    <!-- Gym ID (readonly, from session) -->
                    <div class="mb-3">
                        <label hidden for="gym_id" class="form-label">Gym ID</label>
                        <input hidden type="text" class="form-control" id="gym_id" name="gym_id"
                            value="<?= htmlspecialchars($gym_id) ?>" readonly>
                    </div>

                    <!-- Ads Plan -->
                    <div class="mb-3">
                        <label for="ads_plan" class="form-label">Ads Plan</label>
                        <select class="form-select" id="ads_plan" name="ads_plan" required>
                            <option value="">Select Ads Plan</option>
                            <?php foreach ($plans as $plan): ?>
                                <option value="<?= $plan['plan_id'] ?>" data-price="<?= $plan['price'] ?>">
                                    <?= htmlspecialchars($plan['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Please select an ads plan.</div>
                    </div>



                    <!-- Upload Image -->
                    <div class="mb-3">
                        <label for="image_file" class="form-label">Upload Image</label>
                        <input type="file" class="form-control" id="image_file" name="image_file" accept="image/*" required>
                        <div class="invalid-feedback">Please upload an image file.</div>
                    </div>


                    <!-- Link URL -->
                    <div class="mb-3">
                        <label for="link_url" class="form-label">Link URL</label>
                        <input type="url" class="form-control" id="link_url" name="link_url" required>
                        <div class="invalid-feedback">Please provide a valid link URL.</div>
                    </div>

                    <!-- Amount -->
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount (NPR)</label>
                        <input type="number" class="form-control" id="amount" name="amount" readonly required>
                        <small class="text-muted d-block mt-1">Auto-filled based on selected Ads Plan</small>
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
        // ✅ Auto-fill amount when selecting an ads plan
        document.getElementById("ads_plan").addEventListener("change", function() {
            const selected = this.options[this.selectedIndex];
            const price = selected.getAttribute("data-price");
            document.getElementById("amount").value = price || "";
        });

        // ✅ Bootstrap form validation (no layout change)
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