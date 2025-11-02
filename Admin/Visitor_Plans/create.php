<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// ✅ Get gym_id from session
$gym_id = $_SESSION['gym_id'] ?? null;
if (!$gym_id) {
    die("⚠️ Gym ID not found in session. Please log in again.");
}

// ✅ Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $visitor_fee = trim($_POST['visitor_fee']);
    $status = 'inactive'; // Default value
    $created_at = $updated_at = date('Y-m-d H:i:s');

    // ✅ Validation
    if (!empty($visitor_fee) && is_numeric($visitor_fee)) {
        $stmt = $conn->prepare("
            INSERT INTO visitor_plans (gym_id, visitor_fee, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("idsss", $gym_id, $visitor_fee, $status, $created_at, $updated_at);

        if ($stmt->execute()) {
            header("Location: index.php?status=success&msg=" . urlencode("Visitor plan added successfully!"));
            exit();
        } else {
            header("Location: index.php?status=error&msg=" . urlencode("Database error: " . $stmt->error));
            exit();
        }

        $stmt->close();
    } else {
        header("Location: index.php?status=error&msg=" . urlencode("Invalid input value!"));
        exit();
    }
}
?>

<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Create Visitor Plan</h4>
                <a href="index.php" class="btn btn-light btn-sm border ms-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="create.php">
                    <!-- Visitor Fee -->
                    <div class="mb-3">
                        <label for="visitor_fee" class="form-label">Visitor Fee (Rs.)</label>
                        <input type="number" class="form-control" id="visitor_fee" name="visitor_fee" min="0" step="0.01" placeholder="Enter visitor fee amount" required>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-our px-5 py-2">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php require("../assets/link.php"); ?>
</div>