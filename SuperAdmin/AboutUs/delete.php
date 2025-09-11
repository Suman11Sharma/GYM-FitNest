<?php
include "../../database/db_connect.php";

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $aboutId = (int)$_GET['id'];

    try {
        // Start transaction
        $conn->begin_transaction();

        // Delete points first
        $stmtDelPoints = $conn->prepare("DELETE FROM about_us_points WHERE about_id = ?");
        $stmtDelPoints->bind_param("i", $aboutId);
        $stmtDelPoints->execute();

        // Delete the main card
        $stmtDelCard = $conn->prepare("DELETE FROM about_us WHERE about_id = ?");
        $stmtDelCard->bind_param("i", $aboutId);
        $stmtDelCard->execute();

        // Commit transaction
        $conn->commit();

        header("Location: index.php?status=success&msg=About Us card deleted successfully");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: index.php?status=error&msg=" . urlencode("Failed to delete: " . $e->getMessage()));
        exit();
    }
} else {
    header("Location: index.php?status=error&msg=Invalid request");
    exit();
}
