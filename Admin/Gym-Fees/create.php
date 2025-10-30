<?php
include "../../database/db_connect.php";
session_start();

// ✅ Get gym_id from session
$gym_id = $_SESSION['gym_id'] ?? null;
if (!$gym_id) {
    die("⚠️ Gym ID not found in session. Please log in again.");
}

// ✅ Initialize message
$msg = "";
$msgClass = "";

// ✅ Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $visitor_fee = floatval($_POST['visitor_fee']);
    $created_at = $updated_at = date('Y-m-d H:i:s');

    $sql = "INSERT INTO visitor_plans (gym_id, visitor_fee, created_at, updated_at)
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idss", $gym_id, $visitor_fee, $created_at, $updated_at);

    if ($stmt->execute()) {
        header("Location: index.php?status=success&msg=Fee created successfully");
        $msgClass = "alert-success";
    } else {
        header("Location: index.php?status=error&msg=Fee create failed!");
        $msgClass = "alert-danger";
    }

    $stmt->close();
}
?>

<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Create Gym Fees</h4>
            </div>
            <div class="card-body">

                <!-- ✅ Success / Error Message -->
                <?php if ($msg): ?>
                    <div class="alert <?= $msgClass ?> text-center"><?= htmlspecialchars($msg) ?></div>
                <?php endif; ?>

                <!-- ✅ Form -->
                <form action="" method="POST" class="needs-validation" novalidate>

                    <!-- Hidden Gym ID -->
                    <input type="hidden" name="gym_id" value="<?= htmlspecialchars($gym_id) ?>">

                    <!-- Visitor Fee -->
                    <div class="mb-3">
                        <label for="visitor_fee" class="form-label">Visitor Fee (NPR)</label>
                        <input type="number" class="form-control" id="visitor_fee" name="visitor_fee" min="0" required>
                        <small class="text-muted">Note: Fee is per day basis.</small>
                        <div class="invalid-feedback">Please enter a valid visitor fee.</div>
                    </div>

                    <!-- Submit -->
                    <div class="text-center">
                        <button type="submit" class="btn-our px-5 py-2">Save</button>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <?php require("../assets/link.php"); ?>

    <script>
        // ✅ Bootstrap Form Validation
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