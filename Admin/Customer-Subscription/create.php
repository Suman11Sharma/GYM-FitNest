<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// --- Fetch all plans ---
$plans = [];
$planQuery = "SELECT plan_id, plan_name, duration_days, amount FROM customer_plans WHERE status='active'";
$planResult = mysqli_query($conn, $planQuery);
while ($row = mysqli_fetch_assoc($planResult)) {
    $plans[] = $row;
}
require("../sidelayout.php");

?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Create Customer Subscription</h4>
            </div>
            <div class="card-body p-4">
                <form action="save.php" method="POST">

                    <!-- User ID -->
                    <div class="mb-3">
                        <label for="user_id" class="form-label">User ID</label>
                        <input type="number" class="form-control" id="user_id" name="user_id" required>
                    </div>

                    <!-- Plan Dropdown -->
                    <div class="mb-3">
                        <label for="plan_id" class="form-label">Select Plan</label>
                        <select class="form-select" id="plan_id" name="plan_id" required onchange="updatePlanDetails()">
                            <option value="">Select Plan</option>
                            <?php foreach ($plans as $plan): ?>
                                <option value="<?= $plan['plan_id'] ?>"
                                    data-duration="<?= $plan['duration_days'] ?>"
                                    data-amount="<?= $plan['amount'] ?>">
                                    <?= htmlspecialchars($plan['plan_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Start Date -->
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>

                    <!-- End Date -->
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" readonly>
                    </div>

                    <!-- Amount -->
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" readonly>
                    </div>

                    <!-- Payment Status -->
                    <div class="mb-3">
                        <label for="payment_status" class="form-label">Payment Status</label>
                        <select class="form-select" id="payment_status" name="payment_status" required>
                            <option value="paid" selected>Paid</option>
                        </select>
                    </div>

                    <!-- Transaction ID -->
                    <div class="mb-3">
                        <label for="transaction_id" class="form-label">Transaction ID</label>
                        <input type="text" class="form-control" id="transaction_id" name="transaction_id" value="cash" readonly>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-our px-5 py-2">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
    function updatePlanDetails() {
        const planSelect = document.getElementById('plan_id');
        const selected = planSelect.options[planSelect.selectedIndex];
        if (!selected.value) return;

        const duration = parseInt(selected.dataset.duration);
        const amount = selected.dataset.amount;

        // Set start date to today
        const start = new Date();
        const yyyy = start.getFullYear();
        const mm = String(start.getMonth() + 1).padStart(2, '0');
        const dd = String(start.getDate()).padStart(2, '0');
        const startStr = `${yyyy}-${mm}-${dd}`;
        document.getElementById('start_date').value = startStr;

        // Calculate end date
        const end = new Date(startStr);
        end.setDate(end.getDate() + duration - 1);
        const endY = end.getFullYear();
        const endM = String(end.getMonth() + 1).padStart(2, '0');
        const endD = String(end.getDate()).padStart(2, '0');
        document.getElementById('end_date').value = `${endY}-${endM}-${endD}`;

        // Set amount
        document.getElementById('amount').value = amount;
    }
</script>