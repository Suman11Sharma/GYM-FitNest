<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0 flex-grow-1 text-center">Edit Customer Subscription</h4>
                <a href="index.php" class="btn btn-light btn-sm border ms-3" title="Back to Index">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <div class="card-body p-4">
                <form action="update.php" method="POST" class="needs-validation" novalidate>

                    <!-- Hidden ID (for update) -->
                    <input type="hidden" name="id" value="<?php echo isset($subscription['id']) ? $subscription['id'] : ''; ?>">

                    <!-- User ID -->
                    <div class="mb-3">
                        <label for="user_id" class="form-label">User ID</label>
                        <input type="text" class="form-control" id="user_id" name="user_id"
                            value="<?php echo isset($subscription['user_id']) ? $subscription['user_id'] : ''; ?>" required>
                        <div class="invalid-feedback">Please enter User ID.</div>
                    </div>

                    <!-- Plan ID -->
                    <div class="mb-3">
                        <label for="plan_id" class="form-label">Plan ID</label>
                        <input type="text" class="form-control" id="plan_id" name="plan_id"
                            value="<?php echo isset($subscription['plan_id']) ? $subscription['plan_id'] : ''; ?>" required>
                        <div class="invalid-feedback">Please enter Plan ID.</div>
                    </div>

                    <!-- Gym ID -->
                    <div class="mb-3">
                        <label for="gym_id" class="form-label">Gym ID</label>
                        <input type="text" class="form-control" id="gym_id" name="gym_id"
                            value="<?php echo isset($subscription['gym_id']) ? $subscription['gym_id'] : ''; ?>" required>
                        <div class="invalid-feedback">Please enter Gym ID.</div>
                    </div>

                    <!-- Start Date -->
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date"
                            value="<?php echo isset($subscription['start_date']) ? $subscription['start_date'] : ''; ?>" required>
                        <div class="invalid-feedback">Please select Start Date.</div>
                    </div>

                    <!-- End Date -->
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date"
                            value="<?php echo isset($subscription['end_date']) ? $subscription['end_date'] : ''; ?>" required>
                        <div class="invalid-feedback">Please select End Date.</div>
                    </div>

                    <!-- Amount -->
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" min="0"
                            value="<?php echo isset($subscription['amount']) ? $subscription['amount'] : ''; ?>" required>
                        <div class="invalid-feedback">Please enter a valid amount.</div>
                    </div>

                    <!-- Payment Status -->
                    <div class="mb-3">
                        <label for="payment_status" class="form-label">Payment Status</label>
                        <select class="form-select" id="payment_status" name="payment_status" required>
                            <option value="pending" <?php echo (isset($subscription['payment_status']) && $subscription['payment_status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="paid" <?php echo (isset($subscription['payment_status']) && $subscription['payment_status'] == 'paid') ? 'selected' : ''; ?>>Paid</option>
                            <option value="failed" <?php echo (isset($subscription['payment_status']) && $subscription['payment_status'] == 'failed') ? 'selected' : ''; ?>>Failed</option>
                        </select>
                        <div class="invalid-feedback">Please select Payment Status.</div>
                    </div>

                    <!-- Transaction ID -->
                    <div class="mb-3">
                        <label for="transaction_id" class="form-label">Transaction ID</label>
                        <input type="text" class="form-control" id="transaction_id" name="transaction_id"
                            value="<?php echo isset($subscription['transaction_id']) ? $subscription['transaction_id'] : ''; ?>">
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
    // Bootstrap validation
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