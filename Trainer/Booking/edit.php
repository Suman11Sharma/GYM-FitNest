<?php
include "../../database/db_connect.php";
include "../../database/user_authentication.php";

$booking_id = $_GET['id'] ?? 0;
if (!$booking_id) die("⚠️ Invalid booking ID.");

// Fetch booking details
$stmt = $conn->prepare("
    SELECT b.*, c.full_name 
    FROM trainer_bookings b
    JOIN customers c ON b.user_id = c.customer_id
    WHERE b.booking_id = ?
");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if (!$booking) die("⚠️ Booking not found.");

// Possible booking statuses
$statuses = ["booked", "completed", "cancelled"];
?>
<?php require("../sidelayout.php"); ?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white">
                <h4>Update Booking Status</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="update.php">
                    <input type="hidden" name="booking_id" value="<?= $booking['booking_id']; ?>">

                    <div class="mb-3">
                        <label class="form-label">Customer Name</label>
                        <input type="text" class="form-control"
                            value="<?= htmlspecialchars($booking['full_name']); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Session Date</label>
                        <input type="text" class="form-control"
                            value="<?= htmlspecialchars($booking['session_date']); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Time</label>
                        <input type="text" class="form-control"
                            value="<?= htmlspecialchars($booking['start_time'] . ' - ' . $booking['end_time']); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Amount (Rs.)</label>
                        <input type="text" class="form-control"
                            value="<?= htmlspecialchars($booking['amount']); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Status</label>
                        <input type="text" class="form-control"
                            value="<?= htmlspecialchars(ucfirst($booking['payment_status'])); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Booking Status</label>
                        <select name="status" class="form-select" required>
                            <?php foreach ($statuses as $status): ?>
                                <option value="<?= $status; ?>" <?= $booking['status'] == $status ? 'selected' : ''; ?>>
                                    <?= ucfirst($status); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="text-end">
                        <a href="index.php" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>