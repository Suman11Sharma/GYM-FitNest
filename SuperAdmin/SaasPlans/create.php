<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php"; // adjust path

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect & sanitize form inputs
    $plan_name = mysqli_real_escape_string($conn, $_POST['plan_name']);
    $features = mysqli_real_escape_string($conn, $_POST['features']);
    $amount = (float) $_POST['amount'];
    $duration_months = (int) $_POST['duration_months'];



    // Insert query
    $sql = "INSERT INTO saas_plans (plan_name, features, amount, duration_months) 
            VALUES ('$plan_name', '$features', '$amount', '$duration_months')";

    if (mysqli_query($conn, $sql)) {
        $msg = urlencode("Plan added successfully!");
        header("Location: index.php?status=success&msg=$msg");
        exit;
    } else {
        $msg = urlencode("Error: " . mysqli_error($conn));
        header("Location: index.php?status=error&msg=$msg");
        exit;
    }
}

mysqli_close($conn);
?>


<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Create SaaS Plan</h4>
            </div>
            <div class="card-body">
                <form action="create.php" method="POST" class="needs-validation" novalidate>

                    <!-- Plan Name -->
                    <div class="mb-3">
                        <label for="plan_name" class="form-label">Plan Name</label>
                        <input type="text" class="form-control" id="plan_name" name="plan_name" required>
                        <div class="invalid-feedback">Please enter a plan name.</div>
                    </div>

                    <!-- Features -->
                    <div class="mb-3">
                        <label for="features" class="form-label">Features</label>
                        <textarea class="form-control" id="features" name="features" rows="4" required></textarea>
                        <div class="invalid-feedback">Please list the features of the plan.</div>
                    </div>

                    <!-- Amount -->
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount (in NPR)</label>
                        <input type="number" class="form-control" id="amount" name="amount" min="0" required>
                        <div class="invalid-feedback">Please enter a valid amount.</div>
                    </div>

                    <!-- Duration (Months) -->
                    <div class="mb-3">
                        <label for="duration_months" class="form-label">Duration (Months)</label>
                        <input type="number" class="form-control" id="duration_months" name="duration_months" min="1" required>
                        <div class="invalid-feedback">Please enter duration in months.</div>
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

    <script>
        // Bootstrap form validation
        (() => {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</div>