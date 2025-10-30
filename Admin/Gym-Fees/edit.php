<?php
include "../../database/db_connect.php";
session_start();

// ✅ Get Gym ID from session
$gym_id = $_SESSION['gym_id'] ?? null;
if (!$gym_id) {
    die("⚠️ Gym ID not found in session. Please log in again.");
}

// ✅ Get Fee ID from URL
$fee_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($fee_id <= 0) {
    die("⚠️ Invalid Fee ID.");
}

// ✅ Fetch existing record
$stmt = $conn->prepare("SELECT fee_id, visitor_fee, status FROM visitor_plans WHERE fee_id = ? AND gym_id = ?");
$stmt->bind_param("ii", $fee_id, $gym_id);
$stmt->execute();
$result = $stmt->get_result();
$fee = $result->fetch_assoc();
$stmt->close();

if (!$fee) {
    die("❌ No record found for this Gym Fee ID.");
}

// ✅ Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $visitor_fee = trim($_POST['visitor_fee']);
    $status = trim($_POST['status']);

    if ($visitor_fee === "" || !is_numeric($visitor_fee)) {
        $error = "Visitor fee must be a valid number.";
    } else {
        // 🚫 Check if there’s already another ACTIVE record for this gym
        if ($status === 'active') {
            $check_stmt = $conn->prepare("SELECT fee_id FROM visitor_plans WHERE gym_id = ? AND status = 'active' AND fee_id != ?");
            $check_stmt->bind_param("ii", $gym_id, $fee_id);
            $check_stmt->execute();
            $check_stmt->store_result();

            if ($check_stmt->num_rows > 0) {
                $error = "⚠️ Only one active visitor fee is allowed per gym. Please deactivate the current active one before activating this.";
            }

            $check_stmt->close();
        }

        // ✅ If no validation error, proceed with update
        if (!isset($error)) {
            $update_stmt = $conn->prepare("UPDATE visitor_plans SET visitor_fee = ?, status = ?, updated_at = NOW() WHERE fee_id = ? AND gym_id = ?");
            $update_stmt->bind_param("dsii", $visitor_fee, $status, $fee_id, $gym_id);

            if ($update_stmt->execute()) {
                header("Location: index.php?status=success&msg=Visitor fee updated successfully");
                exit;
            } else {
                $error = "❌ Failed to update record. Please try again.";
            }

            $update_stmt->close();
        }
    }
}
?>

<?php require("../sidelayout.php"); ?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Visitor Gym Fee</h4>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <!-- Visitor Fee -->
                    <div class="mb-3">
                        <label for="visitor_fee" class="form-label fw-bold">Visitor Fee (NPR) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" id="visitor_fee" name="visitor_fee"
                            class="form-control"
                            value="<?= htmlspecialchars($fee['visitor_fee']) ?>" required>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="active" <?= $fee['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $fee['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="text-center">
                        <button type="submit" class="btn-our px-5 py-2">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <?php require("../assets/link.php"); ?>
</div>