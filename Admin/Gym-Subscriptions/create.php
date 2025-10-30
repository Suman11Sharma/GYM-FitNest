<?php
include "../../database/db_connect.php";
session_start();

$gym_id = $_SESSION['gym_id'] ?? null;
if (!$gym_id) die("⚠️ Gym ID not found.");

// Fetch available plans
$plans_stmt = $conn->prepare("SELECT plan_id, plan_name, amount, duration_months FROM saas_plans WHERE status='active'");
$plans_stmt->execute();
$plans = $plans_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$plans_stmt->close();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $plan_id = intval($_POST['plan_id']);
    $start_date = $_POST['start_date'];

    // --- Fetch plan details ---
    $plan_stmt = $conn->prepare("SELECT plan_name, amount, duration_months FROM saas_plans WHERE plan_id=? LIMIT 1");
    $plan_stmt->bind_param("i", $plan_id);
    $plan_stmt->execute();
    $plan = $plan_stmt->get_result()->fetch_assoc();
    $plan_stmt->close();

    if (!$plan) die("❌ Invalid plan selected.");

    $plan_name = $plan['plan_name'];
    $amount = floatval($plan['amount']);
    $duration_months = intval($plan['duration_months']);

    // --- ✅ Calculate END DATE server-side ---
    $start = new DateTime($start_date);
    $end = clone $start;
    $end->modify("+$duration_months months");
    $end_date = $end->format("Y-m-d");

    // --- Insert subscription ---
    $transaction_id = uniqid("TXN_");
    $payment_status = "pending";
    $status = "inactive";
    $created_at = $updated_at = date("Y-m-d H:i:s");

    $stmt = $conn->prepare("INSERT INTO gym_subscriptions 
    (gym_id, plan_name, start_date, end_date, amount, payment_status, transaction_id, status, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "isssssssss",
        $gym_id,
        $plan_name,
        $start_date,
        $end_date,
        $amount,
        $payment_status,
        $transaction_id,
        $status,
        $created_at,
        $updated_at
    );

    if ($stmt->execute()) {
        $stmt->close();
        // Redirect to eSewa
        $query = http_build_query([
            'amount' => $amount,
            'transaction_id' => $transaction_id,
            'plan_name' => $plan_name,
            'gym_id' => $gym_id
        ]);
        header("Location: esewa_process.php?$query");
        exit;
    } else {
        die("❌ Failed: " . $stmt->error);
    }
}
?>

<?php require("../sidelayout.php"); ?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Create Gym Subscription</h4>
            </div>
            <div class="card-body">
                <form method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="gym_id" value="<?= htmlspecialchars($gym_id) ?>">

                    <div class="mb-3">
                        <label for="plan_id" class="form-label">Select Plan</label>
                        <select id="plan_id" name="plan_id" class="form-select" required>
                            <option value="" disabled selected>-- Select Plan --</option>
                            <?php foreach ($plans as $plan): ?>
                                <option value="<?= $plan['plan_id'] ?>"
                                    data-amount="<?= $plan['amount'] ?>"
                                    data-duration="<?= $plan['duration_months'] ?>">
                                    <?= htmlspecialchars($plan['plan_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Please select a plan.</div>
                    </div>

                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" readonly required>
                    </div>

                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" readonly required>
                    </div>

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

        // Set today's date as start date
        const today = new Date().toISOString().split('T')[0];
        startInput.value = today;

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