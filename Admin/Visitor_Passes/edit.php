<?php

include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// ✅ Check if pass_id is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("⚠️ Invalid request.");
}

$pass_id = (int)$_GET['id'];
$gym_id = $_SESSION['gym_id'] ?? null;

if (!$gym_id) {
    die("⚠️ Gym ID not found. Please log in again.");
}

// ✅ Fetch existing visitor pass details
$stmt = $conn->prepare("SELECT name, contact, email, payment_status FROM visitor_passes WHERE pass_id = ? AND gym_id = ?");
$stmt->bind_param("ii", $pass_id, $gym_id);
$stmt->execute();
$result = $stmt->get_result();
$pass = $result->fetch_assoc();
$stmt->close();

if (!$pass) {
    die("❌ Record not found or unauthorized access.");
}

// ✅ Handle update request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $contact = trim($_POST['contact']);
    $email = trim($_POST['email']);
    $payment_status = $_POST['payment_status'];
    $updated_at = date('Y-m-d H:i:s');

    $update_stmt = $conn->prepare("
        UPDATE visitor_passes 
        SET name = ?, contact = ?, email = ?, payment_status = ?, updated_at = ? 
        WHERE pass_id = ? AND gym_id = ?
    ");
    $update_stmt->bind_param("sssssii", $name, $contact, $email, $payment_status, $updated_at, $pass_id, $gym_id);

    if ($update_stmt->execute()) {

        header("Location: index.php?status=success&msg=" . urlencode("Record updated successfully!"));
        exit;
    } else {
        header("Location: index.php?status=error&msg=" . urlencode("Record failed to update"));
    }
}
?>

<?php require("../sidelayout.php"); ?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Edit Visitor Pass</h4>
            </div>
            <div class="card-body">
                <form method="POST">

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control"
                            value="<?= htmlspecialchars($pass['name']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="contact" class="form-label">Contact <span class="text-danger">*</span></label>
                        <input type="text" id="contact" name="contact" class="form-control"
                            value="<?= htmlspecialchars($pass['contact']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" id="email" name="email" class="form-control"
                            value="<?= htmlspecialchars($pass['email']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="payment_status" class="form-label">Payment Status <span class="text-danger">*</span></label>
                        <select id="payment_status" name="payment_status" class="form-select" required>
                            <option value="pending" <?= ($pass['payment_status'] === 'pending') ? 'selected' : '' ?>>Pending</option>
                            <option value="paid" <?= ($pass['payment_status'] === 'paid') ? 'selected' : '' ?>>Paid</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-our">
                            <i class="fas fa-save"></i> Update
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </main>
    <?php require("../assets/link.php"); ?>
</div>

<!-- FontAwesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />