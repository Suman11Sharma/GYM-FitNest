<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aboutId = $_POST['about_id'] ?? null;
    $title = $_POST['card1Title'] ?? '';
    $subtitle = $_POST['card1Subtitle'] ?? '';
    $status = $_POST['status'] ?? 'inactive';
    $descriptions = $_POST['card1Description'] ?? [];

    if (!$aboutId) {
        header("Location: index.php?status=error&msg=Invalid request");
        exit();
    }

    try {
        // Start transaction
        $conn->begin_transaction();

        // If user wants to set active, check how many active already exist
        if ($status === 'active') {
            $stmtCheck = $conn->prepare("SELECT COUNT(*) AS active_count FROM about_us WHERE status='active' AND about_id != ?");
            $stmtCheck->bind_param("i", $aboutId);
            $stmtCheck->execute();
            $res = $stmtCheck->get_result()->fetch_assoc();
            $activeCount = $res['active_count'] ?? 0;

            if ($activeCount >= 3) {
                throw new Exception("Cannot set active. Already 3 active cards exist.");
            }
        }

        // Update about_us
        $stmtUpdate = $conn->prepare("UPDATE about_us SET main_title=?, quotes=?, status=?, updated_at=NOW() WHERE about_id=?");
        $stmtUpdate->bind_param("sssi", $title, $subtitle, $status, $aboutId);
        $stmtUpdate->execute();

        // Delete old points
        $stmtDel = $conn->prepare("DELETE FROM about_us_points WHERE about_id=?");
        $stmtDel->bind_param("i", $aboutId);
        $stmtDel->execute();

        // Insert new points
        $stmtInsertPoint = $conn->prepare("INSERT INTO about_us_points (about_id, description_point) VALUES (?, ?)");
        foreach ($descriptions as $desc) {
            $desc = trim($desc);
            if (!empty($desc)) {
                $stmtInsertPoint->bind_param("is", $aboutId, $desc);
                $stmtInsertPoint->execute();
            }
        }

        // Commit transaction
        $conn->commit();
        header("Location: index.php?status=success&msg=About Us card updated successfully");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: index.php?status=error&msg=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: index.php?status=error&msg=Invalid request method");
    exit();
}
