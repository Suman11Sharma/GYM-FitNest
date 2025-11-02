<?php
include "../database/user_authentication.php";
include "../database/db_connect.php";

$customer_id = $_SESSION['customer_id'];
$gym_id = $_SESSION['gym_id'];

// Fetch active plans for dropdown
$plan_query = "SELECT plan_id, plan_name, duration_days, amount FROM customer_plans WHERE gym_id=? AND status='active'";
$stmt = $conn->prepare($plan_query);
$stmt->bind_param("i", $gym_id);
$stmt->execute();
$plans = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Renew Membership | FitNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow border-0">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Renew Your Membership</h4>
            </div>
            <div class="card-body">
                <form action="renew_process.php" method="POST">
                    <input type="hidden" name="gym_id" value="<?= htmlspecialchars($gym_id); ?>">
                    <input type="hidden" name="customer_id" value="<?= htmlspecialchars($customer_id); ?>">

                    <div class="mb-3">
                        <label for="plan_id" class="form-label">Select Plan</label>
                        <select class="form-select" id="plan_id" name="plan_id" required>
                            <option value="" disabled selected>-- Choose Plan --</option>
                            <?php while ($plan = $plans->fetch_assoc()): ?>
                                <option value="<?= $plan['plan_id'] ?>"
                                    data-days="<?= $plan['duration_days'] ?>"
                                    data-amount="<?= $plan['amount'] ?>">
                                    <?= htmlspecialchars($plan['plan_name']) ?> (<?= $plan['duration_days'] ?> days) - Rs. <?= $plan['amount'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <input type="hidden" name="amount" id="amountField">

                    <div class="text-center">
                        <button type="submit" class="btn btn-dark px-5">Proceed to Pay</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.getElementById("plan_id").addEventListener("change", function() {
            const amount = this.options[this.selectedIndex].getAttribute("data-amount");
            document.getElementById("amountField").value = amount;
        });
    </script>
</body>

</html>