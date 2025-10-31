<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $duration_days = (int)($_POST['duration_days'] ?? 0);
    $price = floatval($_POST['price'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $status = trim($_POST['status'] ?? '');

    if ($id && $name && $duration_days && $price >= 0 && $description && $status) {
        $stmt = $conn->prepare("UPDATE ad_plans SET name=?, duration_days=?, price=?, description=?, status=?, updated_at=NOW() WHERE plan_id=?");
        $stmt->bind_param("sidssi", $name, $duration_days, $price, $description, $status, $id);

        if ($stmt->execute()) {
            header("Location: index.php?status=success&msg=Plan updated successfully");
            exit;
        } else {
            header("Location: edit.php?id=$id&status=error&msg=Failed to update plan");
            exit;
        }
    } else {
        header("Location: edit.php?id=$id&status=error&msg=Invalid form data");
        exit;
    }
} else {
    header("Location: index.php?status=error&msg=Invalid request");
    exit;
}
