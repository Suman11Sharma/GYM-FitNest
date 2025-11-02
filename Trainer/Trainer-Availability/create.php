<?php
include "../../database/admin_authentication.php";
include "../../database/db_connect.php";

// Ensure trainer is logged in
if (!isset($_SESSION['trainer_id'])) {
    header("Location: ../login.php");
    exit();
}

$trainer_id = intval($_SESSION['trainer_id']);

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate input
    $day_of_week = isset($_POST['day_of_week']) ? strtolower(trim($_POST['day_of_week'])) : '';
    $start_time  = isset($_POST['start_time']) ? $_POST['start_time'] : '';
    $end_time    = isset($_POST['end_time']) ? $_POST['end_time'] : '';

    $errors = [];

    if (empty($day_of_week)) {
        $errors[] = "Please select a day of the week.";
    }

    if (empty($start_time) || empty($end_time)) {
        $errors[] = "Start and end time are required.";
    }

    if (strtotime($start_time) >= strtotime($end_time)) {
        $errors[] = "End time must be after start time.";
    }

    if (empty($errors)) {
        // Insert availability into database
        $stmt = $conn->prepare("
            INSERT INTO trainer_availability (trainer_id, day_of_week, start_time, end_time)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("isss", $trainer_id, $day_of_week, $start_time, $end_time);

        if ($stmt->execute()) {
            // Success
            header("Location: index.php?status=success&msg=" . urlencode("Availability added successfully"));

            exit();
        } else {
            $errors[] = "Database error: " . $stmt->error;
        }
    }
}

?>

<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Add Availability</h4>
                <a href="index.php" class="btn btn-light btn-sm border ms-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <div class="card-body p-4">

                <!-- Display errors -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <!-- Day of Week -->
                    <div class="mb-3">
                        <label for="day_of_week" class="form-label">Day of the Week</label>
                        <select class="form-select" id="day_of_week" name="day_of_week" required>
                            <option value="" disabled selected>-- Select Day --</option>
                            <?php
                            // Map full day name to ENUM values
                            $days = [
                                'Sunday' => 'Su',
                                'Monday' => 'Mon',
                                'Tuesday' => 'Tue',
                                'Wednesday' => 'Wed',
                                'Thursday' => 'Thu',
                                'Friday' => 'Fri',
                                'Saturday' => 'Sat'
                            ];

                            foreach ($days as $full => $enum) {
                                echo "<option value='{$enum}'>{$full}</option>";
                            }
                            ?>
                        </select>
                    </div>


                    <!-- Start Time -->
                    <div class="mb-3">
                        <label for="start_time" class="form-label">Start Time</label>
                        <input type="time" class="form-control" id="start_time" name="start_time" required>
                    </div>

                    <!-- End Time -->
                    <div class="mb-3">
                        <label for="end_time" class="form-label">End Time</label>
                        <input type="time" class="form-control" id="end_time" name="end_time" required>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-5 py-2">Submit</button>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <?php require("../assets/link.php"); ?>
</div>