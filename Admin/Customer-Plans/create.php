<?php
include "../../database/db_connect.php";
session_start();

// ✅ Get gym ID from session
$gym_id = $_SESSION['gym_id'] ?? null;
if (!$gym_id) {
    die("⚠️ Gym ID not found in session. Please log in again.");
}

// ✅ Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $plan_name = trim($_POST['plan_name']);
    $duration_days = intval($_POST['duration_days']);
    $amount = floatval($_POST['amount']);
    $status = 'active'; // default status
    $created_at = $updated_at = date('Y-m-d H:i:s');

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO customer_plans (gym_id, plan_name, duration_days, amount, status, created_at, updated_at) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isidsss", $gym_id, $plan_name, $duration_days, $amount, $status, $created_at, $updated_at);

    if ($stmt->execute()) {
        $msg = "✅ Customer plan added successfully!";
        header("Location: index.php?status=success&msg=" . urlencode("Customer plan added successfully!"));
    } else {
        header("Location: index.php?status=error&msg=" . urlencode("Failed to add plan: " . $stmt->error));
    }
    $stmt->close();
}
?>

<?php require("../sidelayout.php"); ?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Create Customer Plan</h4>
            </div>
            <div class="card-body p-4">

                <?php if (!empty($msg)): ?>
                    <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
                <?php endif; ?>

                <form method="POST">

                    <!-- Gym ID (hidden) -->
                    <input type="hidden" name="gym_id" value="<?= htmlspecialchars($gym_id) ?>">

                    <!-- Plan Name -->
                    <div class="mb-3">
                        <label for="plan_name" class="form-label">Plan Name</label>
                        <input type="text" class="form-control" id="plan_name" name="plan_name" required>
                    </div>

                    <!-- Duration Days -->
                    <div class="mb-3">
                        <label for="duration_days" class="form-label">Duration (Days)</label>
                        <input type="number" class="form-control" id="duration_days" name="duration_days" min="1" required>
                    </div>

                    <!-- Amount -->
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" min="0" step="0.01" required>
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
</div>