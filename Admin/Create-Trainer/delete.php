<?php
include "../../database/db_connect.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // ✅ Check if trainer exists
    $check = $conn->prepare("SELECT * FROM trainers WHERE trainer_id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows === 0) {
        header("Location: index.php?status=error&msg=" . urlencode("Trainer not found."));
        exit;
    }

    // ✅ Delete trainer
    $stmt = $conn->prepare("DELETE FROM trainers WHERE trainer_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php?status=success&msg=" . urlencode("Trainer deleted successfully."));
    } else {
        header("Location: index.php?status=error&msg=" . urlencode("Failed to delete trainer."));
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php?status=error&msg=" . urlencode("Invalid request."));
    exit;
}
