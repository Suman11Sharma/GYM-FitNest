<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

$payout_id = $_GET['id'] ?? null;
if (!$payout_id) {
    header("Location: index.php");
    exit;
}

// ✅ Fetch payout details
$sql = "SELECT 
            gp.payout_id, gp.payment_type, gp.amount, gp.payout_status, gp.created_at, gp.paid_at,
            g.gym_id, g.name AS gym_name, g.email AS gym_email, g.phone AS gym_phone
        FROM gym_payouts gp
        JOIN gyms g ON gp.gym_id = g.gym_id
        WHERE gp.payout_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $payout_id);
$stmt->execute();
$result = $stmt->get_result();
$payout = $result->fetch_assoc();
$stmt->close();

if (!$payout) {
    echo "<div class='alert alert-danger'>Invalid payout record.</div>";
    exit;
}

// ✅ Update payout status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['payout_status'];

    if ($status === 'paid') {
        $update_sql = "UPDATE gym_payouts 
                       SET payout_status = ?, paid_at = NOW() 
                       WHERE payout_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $status, $payout_id);
    } else {
        $update_sql = "UPDATE gym_payouts 
                       SET payout_status = ?, paid_at = NULL 
                       WHERE payout_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $status, $payout_id);
    }

    if ($stmt->execute()) {

        header("Location: payments.php?status=success&msg=" . urlencode("Payment updated Succesfully!"));
    } else {
        header("Location: payments.php?status=error&msg=" . urlencode("Payment updated failed"));
    }

    $stmt->close();
}
?>

<?php require("../sidelayout.php"); ?>

<div id="layoutSidenav_content">
    <main class="container mt-4">

        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Payout</h5>
                <a href="index.php" class="btn btn-light btn-sm">← Back</a>
            </div>

            <div class="card-body">
                <form method="POST">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Gym Name</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($payout['gym_name']) ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($payout['gym_email']) ?>" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Contact</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($payout['gym_phone']) ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Payment Type</label>
                            <input type="text" class="form-control" value="<?= ucfirst(str_replace('_', ' ', $payout['payment_type'])) ?>" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Amount (Rs)</label>
                            <input type="text" class="form-control" value="<?= number_format($payout['amount'], 2) ?>" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Payout Status</label>
                            <select name="payout_status" class="form-select" required>
                                <option value="pending" <?= $payout['payout_status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="paid" <?= $payout['payout_status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Paid At</label>
                            <input type="text" class="form-control"
                                value="<?= $payout['paid_at'] ? date("Y-m-d h:i A", strtotime($payout['paid_at'])) : '-' ?>" readonly>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-dark px-4">
                            <i class="fas fa-save me-1"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </main>
</div>

<!-- FontAwesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />