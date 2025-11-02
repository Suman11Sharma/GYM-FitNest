<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";
require "../sidelayout.php";

// ✅ Get user_id and subscription_id from URL
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$subscription_id = isset($_GET['subscription_id']) ? intval($_GET['subscription_id']) : 0;

// --- Fetch the subscription row to get gym_id ---
$subscription = [];
if ($subscription_id) {
    $subQuery = "SELECT * FROM customer_subscriptions WHERE subscription_id = $subscription_id LIMIT 1";
    $subResult = mysqli_query($conn, $subQuery);
    if ($subResult && mysqli_num_rows($subResult) > 0) {
        $subscription = mysqli_fetch_assoc($subResult);
    }
}
$gym_id = $subscription['gym_id'] ?? 0;

// --- Fetch all active plans for this gym ---
$plans = [];
if ($gym_id) {
    $planQuery = "SELECT * FROM customer_plans WHERE gym_id = $gym_id AND status='active'";
    $planResult = mysqli_query($conn, $planQuery);
    while ($row = mysqli_fetch_assoc($planResult)) {
        $plans[] = $row;
    }
}

// --- Determine default start date ---
$today = date('Y-m-d');
$defaultStartDate = $today;

// Check last subscription for this user
$lastQuery = "SELECT * FROM customer_subscriptions 
              WHERE user_id = $user_id AND gym_id = $gym_id 
              ORDER BY end_date DESC LIMIT 1";
$lastResult = mysqli_query($conn, $lastQuery);
if ($lastResult && mysqli_num_rows($lastResult) > 0) {
    $lastSub = mysqli_fetch_assoc($lastResult);
    if ($lastSub['end_date'] >= $today) {
        $defaultStartDate = date('Y-m-d', strtotime($lastSub['end_date'] . ' +1 day'));
    }
}
?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Renew Subscription</h4>
            </div>
            <div class="card-body p-4">
                <form action="store.php" method="POST">

                    <!-- User ID (hidden) -->
                    <input type="hidden" name="user_id" value="<?= $user_id ?>">

                    <!-- Gym ID (hidden) -->
                    <input type="hidden" name="gym_id" value="<?= $gym_id ?>">

                    <!-- Plan Dropdown -->
                    <div class="mb-3">
                        <label for="plan_id" class="form-label">Plan</label>
                        <select class="form-select" id="plan_id" name="plan_id" required onchange="updatePlanDetails()">
                            <option value="">Select Plan</option>
                            <?php foreach ($plans as $plan): ?>
                                <option
                                    value="<?= $plan['plan_id'] ?>"
                                    data-duration="<?= $plan['duration_days'] ?>"
                                    data-amount="<?= $plan['amount'] ?>"
                                    data-start="<?= $defaultStartDate ?>">
                                    <?= htmlspecialchars($plan['plan_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Start Date -->
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $defaultStartDate ?>" readonly>
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
                        <input type="text" class="form-control" name="transaction_id" value="cash" readonly>
                    </div>

                    <!-- Submit -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-our px-5 py-2">Renew</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
    function updatePlanDetails() {
        const planSelect = document.getElementById('plan_id');
        const selectedOption = planSelect.options[planSelect.selectedIndex];
        if (!selectedOption.value) return;

        const duration = parseInt(selectedOption.getAttribute('data-duration'));
        const amount = selectedOption.getAttribute('data-amount');
        const startDate = selectedOption.getAttribute('data-start');

        document.getElementById('start_date').value = startDate;

        // Calculate end date
        const start = new Date(startDate);
        start.setDate(start.getDate() + duration - 1);
        const yyyy = start.getFullYear();
        const mm = String(start.getMonth() + 1).padStart(2, '0');
        const dd = String(start.getDate()).padStart(2, '0');
        document.getElementById('end_date').value = `${yyyy}-${mm}-${dd}`;

        document.getElementById('amount').value = amount;
    }

    // Trigger once on page load
    document.addEventListener('DOMContentLoaded', updatePlanDetails);
</script>