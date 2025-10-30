<?php
include "database/db_connect.php";

$msg = '';

// Get gym_id from GET
$gym_id = intval($_GET['gym_id'] ?? 0);
if ($gym_id === 0) {
    die("Invalid Gym ID.");
}
// 🧮 Fetch per-day visitor fee for this gym
$query = $conn->prepare("SELECT visitor_fee FROM visitor_plans WHERE gym_id = ? LIMIT 1");
$query->bind_param("i", $gym_id);
$query->execute();
$result = $query->get_result();

if ($row = $result->fetch_assoc()) {
    $visitor_fee = floatval($row['visitor_fee']);
} else {
    // Default per-day fee if gym has no custom plan
    $visitor_fee = 100;
}
// ======================
// 1️⃣ Handle form submission
// ======================
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $time_from = $_POST['time_from'] ?? '';
    $time_to = $_POST['time_to'] ?? '';
    $amount = floatval($_POST['amount'] ?? 0);
    $payment_method = $_POST['payment_method'] ?? '';

    // Validate contact
    if (!preg_match('/^\d{7,10}$/', $contact)) {
        $msg = "⚠️ Contact must be 7 to 10 digits.";
    } else {
        $transaction_id = uniqid('TXN_');
        $created_at = date('Y-m-d H:i:s');
        $payment_status = 'Pending';



        if ($payment_method === 'pay_at_visit') {
            // Store record in DB first
            $stmt = $conn->prepare("INSERT INTO visitor_passes 
            (gym_id, name, contact, email, time_from, time_to, amount, payment_method, payment_status, transaction_id, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param(
                "isssssdsssss",
                $gym_id,
                $name,
                $contact,
                $email,
                $time_from,
                $time_to,
                $amount,
                $payment_method,
                $payment_status,
                $transaction_id,
                $created_at,
                $created_at
            );
            $stmt->execute();
            // ✅ Redirect immediately
            header("Location: index.php?status=success&msg=" . urlencode("Pass request submitted! Please pay when you visit."));
            exit();
        } elseif ($payment_method === 'pay_now') {
            // Insert visitor pass record before redirecting
            $stmt = $conn->prepare("INSERT INTO visitor_passes 
        (gym_id, name, contact, email, time_from, time_to, amount, payment_method, payment_status, transaction_id, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param(
                "isssssdsssss",
                $gym_id,
                $name,
                $contact,
                $email,
                $time_from,
                $time_to,
                $amount,
                $payment_method,
                $payment_status,
                $transaction_id,
                $created_at,
                $created_at
            );
            $stmt->execute();

            // ✅ Redirect to eSewa process page
            $query = http_build_query([
                'gym_id' => $gym_id,
                'name' => $name,
                'contact' => $contact,
                'email' => $email,
                'time_from' => $time_from,
                'time_to' => $time_to,
                'amount' => $amount,
                'transaction_id' => $transaction_id
            ]);
            header("Location: esewa_process.php?$query");
            exit();
        } else {
            $msg = "⚠️ Please select a valid payment method.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Get Gym Pass</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h3 class="mb-4 text-center">Get Your Gym Pass</h3>

            <?php if (!empty($msg)): ?>
                <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
            <?php endif; ?>

            <form method="POST" id="visitorPassForm">
                <input type="hidden" name="gym_id" value="<?= $gym_id ?>">

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contact</label>
                    <input type="text" name="contact" class="form-control" required
                        pattern="^\d{7,10}$"
                        title="Please enter 7 to 10 digits only">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">From Date</label>
                        <input type="date" name="time_from" id="time_from" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">To Date</label>
                        <input type="date" name="time_to" id="time_to" class="form-control" required>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label">Amount (NPR)</label>
                    <input type="number" id="amount" name="amount" class="form-control" readonly>
                </div>

                <div class="mb-3 mt-3">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-select" required>
                        <option value="">Select Method</option>
                        <option value="pay_now">Pay Now (eSewa)</option>
                        <option value="pay_at_visit">Pay at Visit</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success w-100">Get Pass</button>
            </form>

        </div>
    </div>


    <script>
        const fromInput = document.getElementById('time_from');
        const toInput = document.getElementById('time_to');
        const today = new Date().toISOString().split('T')[0];
        fromInput.setAttribute('min', today);

        fromInput.addEventListener('change', () => {
            const fromDate = new Date(fromInput.value);
            const minToDate = new Date(fromDate);
            minToDate.setDate(fromDate.getDate() + 4);
            toInput.setAttribute('min', minToDate.toISOString().split('T')[0]);
        });
    </script>

    <script>
        const timeFrom = document.getElementById('time_from');
        const timeTo = document.getElementById('time_to');
        const amountField = document.getElementById('amount');
        const visitorFee = <?= $visitor_fee ?>; // Per-day fee from PHP

        function calculateAmount() {
            const fromDate = new Date(timeFrom.value);
            const toDate = new Date(timeTo.value);

            if (timeFrom.value && timeTo.value && toDate >= fromDate) {
                const diffTime = toDate - fromDate;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // include both days
                const total = diffDays * visitorFee;
                amountField.value = total.toFixed(2);
            } else {
                amountField.value = '';
            }
        }

        timeFrom.addEventListener('change', calculateAmount);
        timeTo.addEventListener('change', calculateAmount);

        // Prevent past date selection
        const presentDay = new Date().toISOString().split('T')[0];
        timeFrom.setAttribute('min', presentDay);
        timeTo.setAttribute('min', presentDay);
    </script>
</body>

</html>