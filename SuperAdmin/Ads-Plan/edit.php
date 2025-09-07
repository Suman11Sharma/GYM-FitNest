<?php
include "../../database/db_connect.php";

// Get the plan ID from URL
$plan_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($plan_id > 0) {
    // Fetch the plan from DB
    $stmt = $conn->prepare("SELECT plan_id, name, duration_days, price, description, status FROM ad_plans WHERE plan_id = ?");
    $stmt->bind_param("i", $plan_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $plan = $result->fetch_assoc();

    if (!$plan) {
        die("❌ Plan not found");
    }
} else {
    die("❌ Invalid plan ID");
}
?>

<?php require("../sidelayout.php"); ?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Edit Ad Plan</h4>
                <a href="index.php" class="btn btn-sm btn-outline-light">←</a>
            </div>
            <div class="card-body">
                <form action="update.php" method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="id" value="<?php echo $plan['plan_id']; ?>">

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Plan Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="<?php echo htmlspecialchars($plan['name']); ?>" required>
                        <div class="invalid-feedback">Please enter the plan name.</div>
                    </div>

                    <!-- Duration (Days) -->
                    <div class="mb-3">
                        <label for="duration_days" class="form-label">Duration (Days)</label>
                        <input type="number" class="form-control" id="duration_days" name="duration_days"
                            value="<?php echo htmlspecialchars($plan['duration_days']); ?>" min="1" required>
                        <div class="invalid-feedback">Please enter a valid duration in days.</div>
                    </div>

                    <!-- Price -->
                    <div class="mb-3">
                        <label for="price" class="form-label">Price (NPR)</label>
                        <input type="number" class="form-control" id="price" name="price"
                            value="<?php echo htmlspecialchars($plan['price']); ?>" min="0" step="0.01" required>
                        <div class="invalid-feedback">Please enter a valid price.</div>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($plan['description']); ?></textarea>
                        <div class="invalid-feedback">Please enter a description.</div>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="" disabled>-- Select Status --</option>
                            <option value="active" <?php echo ($plan['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo ($plan['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                        <div class="invalid-feedback">Please select a status.</div>
                    </div>

                    <!-- Update -->
                    <div class="text-center">
                        <button type="submit" class="btn-our px-5 py-2">Update</button>
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
    </script>
</div>