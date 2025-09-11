<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0 flex-grow-1 text-center">Edit Customer Plan</h4>
                <a href="index.php" class="btn btn-light btn-sm border ms-3" title="Back to Index">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <div class="card-body p-4">
                <form action="update.php" method="POST" class="needs-validation" novalidate>

                    <!-- Gym ID -->
                    <div class="mb-3">
                        <label for="gym_id" class="form-label">Gym ID</label>
                        <input type="text" class="form-control" id="gym_id" name="gym_id" required>
                        <div class="invalid-feedback">Please enter Gym ID.</div>
                    </div>

                    <!-- Plan Name -->
                    <div class="mb-3">
                        <label for="plan_name" class="form-label">Plan Name</label>
                        <input type="text" class="form-control" id="plan_name" name="plan_name" required>
                        <div class="invalid-feedback">Please enter Plan Name.</div>
                    </div>

                    <!-- Duration Days -->
                    <div class="mb-3">
                        <label for="duration_days" class="form-label">Duration (Days)</label>
                        <input type="number" class="form-control" id="duration_days" name="duration_days" min="1" required>
                        <div class="invalid-feedback">Please enter duration in days.</div>
                    </div>

                    <!-- Amount -->
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" min="0" required>
                        <div class="invalid-feedback">Please enter a valid amount.</div>
                    </div>

                    <!-- Submit -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-our px-5 py-2">Update</button>
                    </div>

                </form>
            </div>
        </div>
    </main>
</div>

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