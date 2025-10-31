<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// ✅ Get gym ID from session
$gym_id = $_SESSION['gym_id'] ?? null;
if (!$gym_id) {
    die("⚠️ Gym ID not found in session. Please log in again.");
}

// ✅ Get Plan ID from query string
$plan_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($plan_id <= 0) {
    die("⚠️ Invalid Plan ID.");
}

// ✅ Fetch existing plan for this gym
$stmt = $conn->prepare("SELECT plan_name, duration_days, amount, status FROM customer_plans WHERE plan_id = ? AND gym_id = ?");
$stmt->bind_param("ii", $plan_id, $gym_id);
$stmt->execute();
$result = $stmt->get_result();
$plan = $result->fetch_assoc();
$stmt->close();

if (!$plan) {
    die("❌ No plan found for this Gym ID.");
}

// ✅ Handle form submission
$error = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $plan_name = trim($_POST['plan_name']);
    $duration_days = intval($_POST['duration_days']);
    $amount = floatval($_POST['amount']);
    $status = trim($_POST['status']);

    if ($plan_name === '' || $duration_days <= 0 || $amount <= 0 || !in_array($status, ['active', 'inactive'])) {
        $error = "Please fill all fields correctly.";
    } else {
        $update_stmt = $conn->prepare("UPDATE customer_plans 
                               SET plan_name = ?, duration_days = ?, amount = ?, status = ?, updated_at = NOW() 
                               WHERE plan_id = ? AND gym_id = ?");
        $update_stmt->bind_param("sidsii", $plan_name, $duration_days, $amount, $status, $plan_id, $gym_id);

        if ($update_stmt->execute()) {
            header("Location: index.php?status=success&msg=Customer plan updated successfully");
            exit();
        } else {
            $error = "Failed to update plan. Please try again.";
        }
        $update_stmt->close();
    }
}
?>

<?php require("../sidelayout.php"); ?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Customer Plan</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">

                    <!-- Plan Name -->
                    <div class="mb-3">
                        <label for="plan_name" class="form-label fw-bold">Plan Name</label>
                        <input type="text" id="plan_name" name="plan_name" class="form-control"
                            value="<?= htmlspecialchars($plan['plan_name']) ?>" required>
                    </div>

                    <!-- Duration Days -->
                    <div class="mb-3">
                        <label for="duration_days" class="form-label fw-bold">Duration (Days)</label>
                        <input type="number" id="duration_days" name="duration_days" class="form-control"
                            value="<?= htmlspecialchars($plan['duration_days']) ?>" min="1" required>
                    </div>

                    <!-- Amount -->
                    <div class="mb-3">
                        <label for="amount" class="form-label fw-bold">Amount (NPR)</label>
                        <input type="number" id="amount" name="amount" class="form-control"
                            value="<?= htmlspecialchars($plan['amount']) ?>" min="0" step="0.01" required>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="active" <?= $plan['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $plan['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>

                    <!-- Submit -->
                    <div class="text-center">
                        <button type="submit" class="btn-our px-5 py-2">Update Plan</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <?php require("../assets/link.php"); ?>
</div>