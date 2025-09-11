<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Paid Ads</h4>
            </div>
            <div class="card-body">
                <form action="store.php" method="POST" class="needs-validation" novalidate>

                    <!-- Gym ID -->
                    <div class="mb-3">
                        <label for="gym_id" class="form-label">Gym ID</label>
                        <input type="text" class="form-control" id="gym_id" name="gym_id" required>
                        <div class="invalid-feedback">Please enter the gym ID.</div>
                    </div>

                    <!-- Ads Plan -->
                    <div class="mb-3">
                        <label for="ads_plan" class="form-label">Ads Plan</label>
                        <input type="text" class="form-control" id="ads_plan" name="ads_plan" required>
                        <div class="invalid-feedback">Please enter the ads plan.</div>
                    </div>

                    <!-- Image URL -->
                    <div class="mb-3">
                        <label for="image_url" class="form-label">Image URL</label>
                        <input type="text" class="form-control" id="image_url" name="image_url" required>
                        <div class="invalid-feedback">Please provide the image URL.</div>
                    </div>

                    <!-- Link URL -->
                    <div class="mb-3">
                        <label for="link_url" class="form-label">Link URL</label>
                        <input type="url" class="form-control" id="link_url" name="link_url" required>
                        <div class="invalid-feedback">Please provide a valid link URL.</div>
                    </div>

                    <!-- Start Date -->
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                        <div class="invalid-feedback">Please select a start date.</div>
                    </div>

                    <!-- End Date -->
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                        <div class="invalid-feedback">Please select an end date.</div>
                    </div>

                    <!-- Amount -->
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount (NPR)</label>
                        <input type="number" class="form-control" id="amount" name="amount" min="0" required>
                        <div class="invalid-feedback">Please enter the amount.</div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="" disabled selected>-- Select Payment Method --</option>
                            <option value="esewa">eSewa</option>
                            <option value="cash">Cash</option>
                        </select>
                        <div class="invalid-feedback">Please select a payment method.</div>
                    </div>

                    <!-- Transaction ID -->
                    <div class="mb-3">
                        <label for="transaction_id" class="form-label">Transaction ID</label>
                        <input type="text" class="form-control" id="transaction_id" name="transaction_id" required>
                        <div class="invalid-feedback">Please enter the transaction ID.</div>
                    </div>

                    <!-- Approval Status -->
                    <div class="mb-3">
                        <label for="approval_status" class="form-label">Approval Status</label>
                        <select class="form-select" id="approval_status" name="approval_status" required>
                            <option value="" disabled selected>-- Select Approval Status --</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                        <div class="invalid-feedback">Please select an approval status.</div>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="" disabled selected>-- Select Status --</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="expired">Expired</option>
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
    </script>
</div>