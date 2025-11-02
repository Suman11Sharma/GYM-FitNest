<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// ✅ Validate data
$gym_id = $_SESSION['gym_id'] ?? null;
if (!$gym_id) {
    die("⚠️ Gym ID not found in session.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fee_id = intval($_POST['fee_id']);
    $visitor_fee = trim($_POST['visitor_fee']);
    $status = trim($_POST['status']);
    $updated_at = date('Y-m-d H:i:s');

    // ✅ Check if trying to set another active plan
    if ($status === "active") {
        $check = $conn->prepare("
            SELECT COUNT(*) AS active_count 
            FROM visitor_plans 
            WHERE gym_id = ? AND status = 'active' AND fee_id != ?
        ");
        $check->bind_param("ii", $gym_id, $fee_id);
        $check->execute();
        $result = $check->get_result();
        $row = $result->fetch_assoc();
        $active_count = $row['active_count'];
        $check->close();

        if ($active_count > 0) {
            // Redirect back with error message
            header("Location: edit.php?id=$fee_id&error=" . urlencode("Only one active visitor plan is allowed."));
            exit();
        }
    }

    // ✅ Update query
    $stmt = $conn->prepare("
        UPDATE visitor_plans
        SET visitor_fee = ?, status = ?, updated_at = ?
        WHERE fee_id = ? AND gym_id = ?
    ");
    $stmt->bind_param("dssii", $visitor_fee, $status, $updated_at, $fee_id, $gym_id);

    if ($stmt->execute()) {
        header("Location: index.php?status=success&msg=" . urlencode("Visitor plan updated successfully!"));
        exit();
    } else {
        header("Location: edit.php?id=$fee_id&error=" . urlencode("Database error: " . $stmt->error));
        exit();
    }
    $stmt->close();
} else {
    header("Location: index.php");
    exit();
}
