<?php
include "../../database/admin_authentication.php";
include "../../database/db_connect.php";

// --- Ensure trainer is logged in ---
if (!isset($_SESSION['trainer_id'])) {
    header("Location: ../login.php");
    exit();
}

$trainer_id = intval($_SESSION['trainer_id']);

// --- Get availability ID from URL ---
if (!isset($_GET['id'])) {
    die("❌ Availability ID not specified.");
}
$availability_id = intval($_GET['id']);

// --- Fetch availability for this trainer ---
$stmt = $conn->prepare("SELECT * FROM trainer_availability WHERE availability_id = ? AND trainer_id = ? LIMIT 1");
$stmt->bind_param("ii", $availability_id, $trainer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("❌ No availability found for this trainer.");
}

$availability = $result->fetch_assoc();

// --- Handle POST submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $day_of_week = $_POST['day_of_week'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $updateStmt = $conn->prepare("
        UPDATE trainer_availability 
        SET day_of_week = ?, start_time = ?, end_time = ? 
        WHERE availability_id = ? AND trainer_id = ?
    ");
    $updateStmt->bind_param("sssii", $day_of_week, $start_time, $end_time, $availability_id, $trainer_id);
    if ($updateStmt->execute()) {
        header("Location: index.php?status=success&msg=" . urlencode("Availability updated successfully"));

        exit();
    } else {
        header("Location: index.php?status=error&msg=" . urlencode("Availability updated failed"));
    }
}
require("../sidelayout.php");

?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Edit Availability</h4>
                <a href="index.php" class="btn btn-light btn-sm border ms-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <div class="card-body p-4">
                <form method="POST">
                    <!-- Day of Week -->
                    <div class="mb-3">
                        <label for="day_of_week" class="form-label">Day of the Week</label>
                        <select class="form-select" id="day_of_week" name="day_of_week" required>
                            <?php
                            $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                            foreach ($days as $day) {
                                $selected = ($availability['day_of_week'] === $day) ? 'selected' : '';
                                echo "<option value='{$day}' {$selected}>" . ucfirst($day) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Start Time -->
                    <div class="mb-3">
                        <label for="start_time" class="form-label">Start Time</label>
                        <input type="time" class="form-control" id="start_time" name="start_time"
                            value="<?= htmlspecialchars($availability['start_time']); ?>" required>
                    </div>

                    <!-- End Time -->
                    <div class="mb-3">
                        <label for="end_time" class="form-label">End Time</label>
                        <input type="time" class="form-control" id="end_time" name="end_time"
                            value="<?= htmlspecialchars($availability['end_time']); ?>" required>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="btn-our px-5 py-2">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php require("../assets/link.php"); ?>
</div>