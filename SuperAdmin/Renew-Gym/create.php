<?php
include "../../database/user_authentication.php";
include("../../database/db_connect.php");

// ✅ Fetch Active Plans
$plansQuery = "SELECT plan_id, plan_name, features, amount, duration_months 
               FROM saas_plans 
               WHERE status = 'active'";
$plansResult = $conn->query($plansQuery);
$plans = $plansResult ? $plansResult->fetch_all(MYSQLI_ASSOC) : [];

// ✅ Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gym_id = trim($_POST['gym_id'] ?? '');
    $plan_id = intval($_POST['plan_id'] ?? 0);
    $start_date = $_POST['start_date'] ?? date('Y-m-d');
    $end_date = $_POST['end_date'] ?? null;
    $amount = floatval($_POST['amount'] ?? 0);

    if (empty($gym_id) || empty($plan_id) || empty($start_date) || empty($end_date)) {
        die("<script>alert('❌ Please fill in all required fields.'); window.history.back();</script>");
    }

    // Payment and status defaults
    $payment_status = 'Paid';
    $transaction_id = 'cash';
    $status = 'active';

    // ✅ Insert into gym_subscriptions
    $sql = "INSERT INTO gym_subscriptions 
            (gym_id, plan_name, start_date, end_date, amount, payment_status, transaction_id, status, created_at)
            SELECT ?, plan_name, ?, ?, ?, ?, ?, ?, NOW()
            FROM saas_plans WHERE plan_id = ? LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssi",
        $gym_id,
        $start_date,
        $end_date,
        $amount,
        $payment_status,
        $transaction_id,
        $status,
        $plan_id
    );

    if ($stmt->execute()) {
        header("Location: index.php?status=success&msg=" . urlencode("Gym subscription created successfully!"));
        exit;
    } else {
        die("❌ Database Error: " . $stmt->error);
    }
}
require("../sidelayout.php");

?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Create Gym Subscription</h4>
            </div>
            <div class="card-body">
                <form method="POST" class="needs-validation" novalidate>
                    <!-- GYM ID -->
                    <div class="mb-3">
                        <label for="gym_id" class="form-label">Gym ID <span class="text-danger">*</span></label>
                        <input type="text" id="gym_id" name="gym_id" class="form-control" required>
                        <div class="invalid-feedback">Please enter Gym ID.</div>
                    </div>

                    <!-- PLAN -->
                    <div class="mb-3">
                        <label for="plan_id" class="form-label">Select Plan <span class="text-danger">*</span></label>
                        <select id="plan_id" name="plan_id" class="form-select" required>
                            <option value="" disabled selected>-- Select Plan --</option>
                            <?php foreach ($plans as $plan): ?>
                                <option value="<?= $plan['plan_id'] ?>"
                                    data-amount="<?= $plan['amount'] ?>"
                                    data-duration="<?= $plan['duration_months'] ?>">
                                    <?= htmlspecialchars($plan['plan_name']) ?> (<?= $plan['duration_months'] ?> months)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Please select a plan.</div>
                    </div>

                    <!-- START DATE -->
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" readonly required>
                    </div>

                    <!-- END DATE -->
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" readonly required>
                    </div>

                    <!-- AMOUNT -->
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount (NPR)</label>
                        <input type="number" id="amount" name="amount" class="form-control" readonly required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn-our px-5 py-2">Proceed to Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php require("../assets/link.php"); ?>

    <script>
        const planSelect = document.getElementById('plan_id');
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');
        const amountInput = document.getElementById('amount');

        // Set today's date
        const today = new Date().toISOString().split('T')[0];
        startInput.value = today;

        // Update on plan change
        planSelect.addEventListener('change', updateDetails);

        function updateDetails() {
            const selected = planSelect.selectedOptions[0];
            if (!selected) return;

            const duration = parseInt(selected.dataset.duration || 0);
            const amount = parseFloat(selected.dataset.amount || 0);
            const start = new Date(startInput.value);
            const end = new Date(start);
            end.setMonth(end.getMonth() + duration);

            endInput.value = end.toISOString().split('T')[0];
            amountInput.value = amount.toFixed(2);
        }
    </script>
</div>