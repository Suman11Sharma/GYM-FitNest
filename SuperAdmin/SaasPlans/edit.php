<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// Validate `id` param
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?status=error&msg=Invalid Plan ID");
    exit();
}
$plan_id = (int) $_GET['id'];

// Fetch the plan from DB
$sql = "SELECT * FROM saas_plans WHERE plan_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $plan_id);
$stmt->execute();
$result = $stmt->get_result();
$plan = $result->fetch_assoc();

if (!$plan) {
    header("Location: index.php?status=error&msg=Plan not found");
    exit();
}
?>
<?php require("../sidelayout.php"); ?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Edit SaaS Plan</h4>
                <a href="index.php" class="btn btn-light btn-sm border ms-3" title="Back to SaaS Plans">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <div class="card-body">
                <form action="update.php" method="POST" class="needs-validation" novalidate>

                    <!-- Hidden ID -->
                    <input type="hidden" name="plan_id" value="<?= htmlspecialchars($plan['plan_id']) ?>">

                    <!-- Plan Name -->
                    <div class="mb-3">
                        <label for="plan_name" class="form-label">Plan Name</label>
                        <input type="text" class="form-control" id="plan_name" name="plan_name"
                            value="<?= htmlspecialchars($plan['plan_name']) ?>" required>
                        <div class="invalid-feedback">Please enter a plan name.</div>
                    </div>

                    <!-- Features -->
                    <div class="mb-3">
                        <label for="features" class="form-label">Features</label>
                        <textarea class="form-control" id="features" name="features" rows="4" required><?= htmlspecialchars($plan['features']) ?></textarea>
                        <div class="invalid-feedback">Please list the features of the plan.</div>
                    </div>

                    <!-- Amount -->
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount (in NPR)</label>
                        <input type="number" class="form-control" id="amount" name="amount" min="0"
                            value="<?= htmlspecialchars($plan['amount']) ?>" required>
                        <div class="invalid-feedback">Please enter a valid amount.</div>
                    </div>

                    <!-- Duration (Months) -->
                    <div class="mb-3">
                        <label for="duration_months" class="form-label">Duration (Months)</label>
                        <input type="number" class="form-control" id="duration_months" name="duration_months" min="1"
                            value="<?= htmlspecialchars($plan['duration_months']) ?>" required>
                        <div class="invalid-feedback">Please enter duration in months.</div>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active" <?= $plan['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $plan['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                        <div class="invalid-feedback">Please select a status.</div>
                    </div>

                    <!-- Submit -->
                    <div class="text-center">
                        <button type="submit" class="btn-our px-5 py-2">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

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
</script>