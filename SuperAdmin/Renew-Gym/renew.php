<?php
include "../../database/user_authentication.php";
include("../../database/db_connect.php");

// ✅ Get gym_id from GET
$gym_id = isset($_GET['gym_id']) ? intval($_GET['gym_id']) : 0;
if (!$gym_id) {
    die("<script>alert('Invalid Gym ID'); window.location='index.php';</script>");
}

// ✅ Fetch Active Plans
$plansQuery = "SELECT plan_id, plan_name, features, amount, duration_months 
               FROM saas_plans 
               WHERE status = 'active'";
$plansResult = $conn->query($plansQuery);
$plans = $plansResult ? $plansResult->fetch_all(MYSQLI_ASSOC) : [];

// ✅ Fetch last subscription of this gym
$lastSubQuery = "SELECT * FROM gym_subscriptions 
                 WHERE gym_id = ? 
                 ORDER BY end_date DESC LIMIT 1";
$stmt = $conn->prepare($lastSubQuery);
$stmt->bind_param("i", $gym_id);
$stmt->execute();
$lastSubResult = $stmt->get_result();
$lastSub = $lastSubResult->fetch_assoc();

// ✅ Determine default start date
$today = date('Y-m-d');
if ($lastSub) {
    $end_date_prev = $lastSub['end_date'];
    if ($end_date_prev < $today) {
        // expired, start today
        $start_date_default = $today;
    } else {
        // active, start next day after last end date
        $start_date_default = date('Y-m-d', strtotime($end_date_prev . ' +1 day'));
    }
} else {
    // no previous subscription
    $start_date_default = $today;
}

// ✅ Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plan_id = intval($_POST['plan_id'] ?? 0);
    $start_date = $_POST['start_date'] ?? $start_date_default;
    $end_date = $_POST['end_date'] ?? null;
    $amount = floatval($_POST['amount'] ?? 0);

    if (!$plan_id || !$start_date || !$end_date) {
        die("<script>alert('Please fill all required fields'); window.history.back();</script>");
    }

    $payment_status = 'Paid';
    $transaction_id = 'cash';
    $status = 'active';

    // Insert subscription
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
        header("Location: ../superAdminPage.php?status=success&msg=" . urlencode("Gym subscription renewed successfully!"));
        exit;
    } else {
        die("Database Error: " . $stmt->error);
    }
}

require("../sidelayout.php");
?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Renew Gym Subscription</h4>
            </div>
            <div class="card-body">
                <form method="POST" class="needs-validation" novalidate>
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
                        <input type="date" id="start_date" name="start_date" class="form-control" readonly value="<?= $start_date_default ?>" required>
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
                        <button type="submit" class="btn-our px-5 py-2">Proceed</button>
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

        planSelect.addEventListener('change', updateDetails);

        // trigger default if pre-selected
        updateDetails();
    </script>
</div>