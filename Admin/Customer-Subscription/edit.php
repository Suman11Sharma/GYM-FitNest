<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";
require("../sidelayout.php");

// --- Fetch subscription based on ID ---
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$subscription = [];

if ($id) {
    $query = "SELECT cs.*, c.full_name, cp.plan_name 
              FROM customer_subscriptions cs
              LEFT JOIN customers c ON cs.user_id = c.customer_id
              LEFT JOIN customer_plans cp ON cs.plan_id = cp.plan_id
              WHERE cs.subscription_id = $id
              LIMIT 1";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $subscription = mysqli_fetch_assoc($result);
    } else {
        echo "<div class='alert alert-danger'>Subscription not found.</div>";
        exit;
    }
}
?>

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

                    <!-- Hidden ID -->
                    <input type="hidden" name="id" value="<?= $subscription['subscription_id'] ?>">

                    <!-- Username (readonly) -->
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($subscription['full_name']) ?>" readonly>
                    </div>

                    <!-- Plan Name (readonly) -->
                    <div class="mb-3">
                        <label class="form-label">Plan Name</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($subscription['plan_name']) ?>" readonly>
                    </div>

                    <!-- Start Date (readonly) -->
                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control" value="<?= $subscription['start_date'] ?>" readonly>
                    </div>

                    <!-- End Date (readonly) -->
                    <div class="mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control" value="<?= $subscription['end_date'] ?>" readonly>
                    </div>

                    <!-- Amount (readonly) -->
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" class="form-control" value="<?= $subscription['amount'] ?>" readonly>
                    </div>

                    <!-- Payment Status (editable) -->
                    <div class="mb-3">
                        <label for="payment_status" class="form-label">Payment Status</label>
                        <select class="form-select" id="payment_status" name="payment_status" required>
                            <option value="pending" <?= ($subscription['payment_status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                            <option value="paid" <?= ($subscription['payment_status'] == 'paid') ? 'selected' : '' ?>>Paid</option>
                            <option value="failed" <?= ($subscription['payment_status'] == 'failed') ? 'selected' : '' ?>>Failed</option>
                        </select>
                        <div class="invalid-feedback">Please select Payment Status.</div>
                    </div>

                    <!-- Status (editable) -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active" <?= ($subscription['status'] == 'active') ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= ($subscription['status'] == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                        </select>
                        <div class="invalid-feedback">Please select Status.</div>
                    </div>

                    <!-- Transaction ID (readonly) -->
                    <div class="mb-3">
                        <label class="form-label">Transaction ID</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($subscription['transaction_id']) ?>" readonly>
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