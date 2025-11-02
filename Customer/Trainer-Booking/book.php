<?php
include "../../database/db_connect.php";
session_start();

$gym_id = $_SESSION['gym_id'] ?? 0;
$user_id = $_SESSION['customer_id'] ?? 0;
if (!$gym_id || !$user_id) die("Session invalid.");

$trainer_id = $_GET['trainer_id'] ?? 0;
if (!$trainer_id) die("Trainer not selected.");

// Fetch trainer info
$stmt = $conn->prepare("SELECT name, rate_per_session FROM trainers WHERE trainer_id=? AND gym_id=? LIMIT 1");
$stmt->bind_param("ii", $trainer_id, $gym_id);
$stmt->execute();
$trainer = $stmt->get_result()->fetch_assoc();
if (!$trainer) die("Trainer not found.");

// Fetch available slots
$avail_stmt = $conn->prepare("SELECT availability_id, day_of_week, start_time, end_time FROM trainer_availability WHERE trainer_id=?");
$avail_stmt->bind_param("i", $trainer_id);
$avail_stmt->execute();
$availabilities = $avail_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Handle booking submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $availability_id = $_POST['availability_id'];
    $session_date = $_POST['session_date'];

    // Fetch slot info
    $stmt2 = $conn->prepare("SELECT start_time, end_time FROM trainer_availability WHERE availability_id=? LIMIT 1");
    $stmt2->bind_param("i", $availability_id);
    $stmt2->execute();
    $avail = $stmt2->get_result()->fetch_assoc();

    // Check if already booked
    $stmt3 = $conn->prepare("SELECT COUNT(*) AS cnt FROM trainer_bookings WHERE trainer_id=? AND session_date=? AND start_time=? AND end_time=?");
    $stmt3->bind_param("isss", $trainer_id, $session_date, $avail['start_time'], $avail['end_time']);
    $stmt3->execute();
    $alreadyBooked = $stmt3->get_result()->fetch_assoc()['cnt'];

    if ($alreadyBooked > 0) {
        $error = "This slot is already booked. Choose another.";
    } else {
        // Create pending booking
        $transaction_id = uniqid("txn_");
        $amount = $trainer['rate_per_session'];

        $stmt4 = $conn->prepare("
            INSERT INTO trainer_bookings 
            (trainer_id, user_id, gym_id, session_date, start_time, end_time, amount, payment_status, status, transaction_id, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', 'booked', ?, NOW(), NOW())
        ");
        $stmt4->bind_param("iiissdss", $trainer_id, $user_id, $gym_id, $session_date, $avail['start_time'], $avail['end_time'], $amount, $transaction_id);

        if ($stmt4->execute()) {
            // Redirect to eSewa
            $esewa_url = "https://rc-epay.esewa.com.np/api/epay/main/v2/form";
            $success_url = "http://localhost/GYM-FitNest/Customer/Trainer-Booking/success_esewa.php?transaction_id={$transaction_id}";
            $failure_url = "http://localhost/GYM-FitNest/Customer/Trainer_Booking/fail_esewa.php";
            $product_code = "EPAYTEST";
            $secret = "8gBm/:&EnhH.1/q";

            $message = "total_amount={$amount},transaction_uuid={$transaction_id},product_code={$product_code}";
            $signature = base64_encode(hash_hmac('sha256', $message, $secret, true));

            echo "
            <form id='esewaForm' action='{$esewa_url}' method='POST'>
                <input type='hidden' name='amount' value='{$amount}'>
                <input type='hidden' name='tax_amount' value='0'>
                <input type='hidden' name='total_amount' value='{$amount}'>
                <input type='hidden' name='transaction_uuid' value='{$transaction_id}'>
                <input type='hidden' name='product_code' value='{$product_code}'>
                <input type='hidden' name='product_service_charge' value='0'>
                <input type='hidden' name='product_delivery_charge' value='0'>
                <input type='hidden' name='success_url' value='{$success_url}'>
                <input type='hidden' name='failure_url' value='{$failure_url}'>
                <input type='hidden' name='signed_field_names' value='total_amount,transaction_uuid,product_code'>
                <input type='hidden' name='signature' value='{$signature}'>
            </form>
            <script>document.getElementById('esewaForm').submit();</script>
            ";
        } else {
            $error = "Failed to create booking.";
        }
    }
}
?>

<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main class="container mt-4">
        <h3>Book Trainer: <?= htmlspecialchars($trainer['name']); ?></h3>
        <?php if (!empty($error)) echo "<div class='alert alert-danger'>{$error}</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="availability_id" class="form-label">Available Slots</label>
                <select class="form-select" id="availability_id" name="availability_id" required>
                    <option value="" disabled selected>-- Select Slot --</option>
                    <?php foreach ($availabilities as $a):
                        $day = ucfirst($a['day_of_week']);
                        $time = $a['start_time'] . " - " . $a['end_time']; ?>
                        <option value="<?= $a['availability_id']; ?>"><?= $day ?>: <?= $time ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="session_date" class="form-label">Session Date</label>
                <input type="date" class="form-control" name="session_date" min="<?= date('Y-m-d'); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Amount (Rs.)</label>
                <input type="text" class="form-control" value="<?= $trainer['rate_per_session']; ?>" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Pay & Book</button>
        </form>
    </main>
    <?php require("../assets/link.php"); ?>
</div>