<?php
include "../../database/db_connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id'] ?? 0);
    $role = $_POST['role'] ?? '';
    $gym_id = $_POST['gym_id'] ?? null;
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate required fields
    if (!$user_id || !$role || !$name || !$email || !$phone) {
        header("Location: index.php?status=error&msg=" . urlencode("Missing required fields."));
        exit;
    }

    // If role is superadmin, make gym_id NULL
    if (strtolower($role) === 'superadmin') {
        $gym_id = null;
    }

    try {
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users 
                    SET role = ?, gym_id = ?, name = ?, email = ?, phone = ?, password = ? 
                    WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception($conn->error);
            $stmt->bind_param("ssssssi", $role, $gym_id, $name, $email, $phone, $hashed_password, $user_id);
        } else {
            $sql = "UPDATE users 
                    SET role = ?, gym_id = ?, name = ?, email = ?, phone = ? 
                    WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception($conn->error);
            $stmt->bind_param("sssssi", $role, $gym_id, $name, $email, $phone, $user_id);
        }

        if ($stmt->execute()) {
            header("Location: index.php?status=success&msg=" . urlencode("User updated successfully."));
            exit;
        } else {
            // Execution failed
            header("Location: index.php?status=error&msg=" . urlencode("Update failed: " . $stmt->error));
            exit;
        }
    } catch (Exception $e) {
        header("Location: index.php?status=error&msg=" . urlencode("Database error: " . $e->getMessage()));
        exit;
    }
} else {
    // Invalid request method
    header("Location: index.php?status=error&msg=" . urlencode("Invalid request."));
    exit;
}
