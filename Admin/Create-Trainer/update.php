<?php
include "../../database/db_connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $trainer_id = intval($_POST['trainer_id']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $specialization = trim($_POST['specialization']);
    $rate = trim($_POST['rate_per_session']);
    $status = $_POST['status'];
    $password_raw = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // ✅ Validation
    if (empty($name) || empty($email) || empty($phone) || empty($specialization)) {
        header("Location: edit.php?id=$trainer_id&status=error&msg=" . urlencode("Please fill all required fields."));
        exit;
    }

    // ✅ Check password match
    if (!empty($password_raw) && $password_raw !== $confirm_password) {
        header("Location: edit.php?id=$trainer_id&status=error&msg=" . urlencode("Passwords do not match."));
        exit;
    }

    // ✅ Prepare update query
    if (!empty($password_raw)) {
        // Hash and update password
        $hashed_password = password_hash($password_raw, PASSWORD_DEFAULT);
        $query = "UPDATE trainers 
                  SET name=?, email=?, phone=?, specialization=?, rate_per_session=?, status=?, password=?, updated_at=NOW() 
                  WHERE trainer_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssdsi", $name, $email, $phone, $specialization, $rate, $status, $hashed_password, $trainer_id);
    } else {
        // Update without changing password
        $query = "UPDATE trainers 
                  SET name=?, email=?, phone=?, specialization=?, rate_per_session=?, status=?, updated_at=NOW() 
                  WHERE trainer_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssi", $name, $email, $phone, $specialization, $rate, $status, $trainer_id);
    }

    if ($stmt->execute()) {
        header("Location: index.php?status=success&msg=" . urlencode("Trainer updated successfully."));
        exit;
    } else {
        header("Location: edit.php?id=$trainer_id&status=error&msg=" . urlencode("Failed to update trainer."));
        exit;
    }
} else {
    header("Location: index.php?status=error&msg=" . urlencode("Invalid request method."));
    exit;
}
