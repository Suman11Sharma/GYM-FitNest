<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// Get subscription ID from URL
$id = intval($_GET['id'] ?? 0);

// Fetch current subscription
$stmt = $conn->prepare("SELECT * FROM gym_subscriptions WHERE subscription_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$subscription = $stmt->get_result()->fetch_assoc();

if (!$subscription) {
    header("Location: index.php?status=success&msg=" . urlencode("Subscription not found"));

    exit;
}

// Fetch gym name using gym_id
$gym_stmt = $conn->prepare("SELECT name FROM gyms WHERE gym_id = ?");
$gym_stmt->bind_param("i", $subscription['gym_id']);
$gym_stmt->execute();
$gym = $gym_stmt->get_result()->fetch_assoc();
$gym_name = $gym['name'] ?? 'Unknown Gym';

// Fetch active plans
$plans = $conn->query("SELECT * FROM saas_plans WHERE status = 'active'")->fetch_all(MYSQLI_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $gym_id = $_POST['gym_id'];
    $plan_id = $_POST['plan_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $amount = $_POST['amount'];
    $payment_status = $_POST['payment_status'];
    $status = $_POST['status'];

    // Fetch selected plan details
    $plan_stmt = $conn->prepare("SELECT * FROM saas_plans WHERE plan_id = ?");
    $plan_stmt->bind_param("i", $plan_id);
    $plan_stmt->execute();
    $plan = $plan_stmt->get_result()->fetch_assoc();

    if ($plan && $plan['plan_name'] !== $subscription['plan_name']) {
        // 🆕 New plan selected → insert new record
        $insert = $conn->prepare("INSERT INTO gym_subscriptions 
            (gym_id, plan_name, start_date, end_date, amount, payment_status, transaction_id, status, created_at) 
            VALUES (?, ?, ?, ?, ?, 'paid', 'cash', 'active', NOW())");
        $insert->bind_param("isssd", $gym_id, $plan['plan_name'], $start_date, $end_date, $plan['amount']);
        $insert->execute();

        header("Location: index.php?status=success&msg=" . urlencode("New subscription added successfully."));

        exit;
    } else {
        // ✏️ Update existing record
        $update = $conn->prepare("UPDATE gym_subscriptions 
            SET payment_status = ?, status = ?, updated_at = NOW() 
            WHERE subscription_id = ?");
        $update->bind_param("ssi", $payment_status, $status, $id);
        $update->execute();
        header("Location: index.php?status=success&msg=" . urlencode("Subscription updated successfully."));
        exit;
    }
}
require("../sidelayout.php");
?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Edit Gym Subscription</h4>
            </div>
            <div class="card-body">
                <form method="POST" class="needs-validation" novalidate>
                    <!-- Gym Info -->
                    <div class="mb-3">
                        <label>Gym ID</label>
                        <input type="text" name="gym_id" class="form-control"
                            value="<?= htmlspecialchars($subscription['gym_id']) ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Gym Name</label>
                        <input type="text" class="form-control"
                            value="<?= htmlspecialchars($gym_name) ?>" readonly>
                    </div>

                    <!-- Plan Info -->
                    <div class="mb-3">
                        <label for="plan_id" class="form-label">Select Plan</label>
                        <select id="plan_id" name="plan_id" class="form-select" required>
                            <?php foreach ($plans as $plan): ?>
                                <option value="<?= $plan['plan_id'] ?>"
                                    data-amount="<?= $plan['amount'] ?>"
                                    data-duration="<?= $plan['duration_months'] ?>"
                                    <?= ($plan['plan_name'] === $subscription['plan_name']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($plan['plan_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Please select a plan.</div>
                    </div>

                    <!-- Dates -->
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" readonly
                            value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" readonly required>
                    </div>

                    <!-- Amount -->
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount (NPR)</label>
                        <input type="number" id="amount" name="amount" class="form-control" readonly required>
                    </div>

                    <!-- Payment & Status -->
                    <div class="mb-3">
                        <label for="payment_status" class="form-label">Payment Status</label>
                        <select name="payment_status" class="form-select" required>
                            <option value="paid" <?= $subscription['payment_status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
                            <option value="pending" <?= $subscription['payment_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active" <?= $subscription['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $subscription['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>

                    <!-- Submit -->
                    <div class="text-center">
                        <button type="submit" class="btn-our px-5 py-2">Save Changes</button>
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

        // Initialize
        updateDetails();

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