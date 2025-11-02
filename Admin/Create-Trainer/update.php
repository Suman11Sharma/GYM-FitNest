<?php
include "../../database/db_connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['trainer_id']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $specialization = trim($_POST['specialization']);
    $rate = trim($_POST['rate_per_session']);
    $status = $_POST['status'] ?? 'inactive';
    $password = trim($_POST['password']);

    if (empty($name) || empty($email) || empty($phone) || empty($specialization) || empty($rate)) {
        header("Location: index.php?status=error&msg=All required fields must be filled");
        exit;
    }

    // Update with or without password
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE trainers 
            SET name=?, email=?, phone=?, specialization=?, rate_per_session=?, status=?, password=?, updated_at=NOW() 
            WHERE trainer_id=?");
        $stmt->bind_param("sssssssi", $name, $email, $phone, $specialization, $rate, $status, $hashed_password, $id);
    } else {
        $stmt = $conn->prepare("UPDATE trainers 
            SET name=?, email=?, phone=?, specialization=?, rate_per_session=?, status=?, updated_at=NOW() 
            WHERE trainer_id=?");
        $stmt->bind_param("ssssssi", $name, $email, $phone, $specialization, $rate, $status, $id);
    }

    if ($stmt->execute()) {
        header("Location: index.php?status=success&msg=Trainer updated successfully");
    } else {
        header("Location: index.php?status=error&msg=Failed to update trainer");
    }

    $stmt->close();
    exit;
}
header("Location: index.php?status=error&msg=Invalid request");
