<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// ✅ Validate and fetch record
$gym_id = $_SESSION['gym_id'] ?? null;
if (!$gym_id) die("⚠️ Gym ID not found in session.");

$fee_id = $_GET['id'] ?? null;
if (!$fee_id) die("⚠️ Invalid request.");

$stmt = $conn->prepare("SELECT * FROM visitor_plans WHERE fee_id = ? AND gym_id = ?");
$stmt->bind_param("ii", $fee_id, $gym_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("❌ Visitor plan not found or unauthorized access.");
}
$plan = $result->fetch_assoc();
$stmt->close();
?>

<?php require("../sidelayout.php"); ?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Edit Visitor Plan</h4>
                <a href="index.php" class="btn btn-light btn-sm border">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>

            <div class="card-body p-4">
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>

                <form method="POST" action="update.php">
                    <input type="hidden" name="fee_id" value="<?= htmlspecialchars($plan['fee_id']); ?>">

                    <!-- Visitor Fee -->
                    <div class="mb-3">
                        <label for="visitor_fee" class="form-label">Visitor Fee</label>
                        <input type="number" class="form-control" id="visitor_fee" name="visitor_fee"
                            value="<?= htmlspecialchars($plan['visitor_fee']); ?>" min="0" step="0.01" required>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active" <?= $plan['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?= $plan['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>

                    <!-- Submit -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-our px-5 py-2">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php require("../assets/link.php"); ?>
</div>