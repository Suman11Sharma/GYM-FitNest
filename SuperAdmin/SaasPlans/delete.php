<?php
include "../../database/db_connect.php";

// Check if ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $plan_id = intval($_GET['id']);

    // Prepare delete query
    $sql = "DELETE FROM saas_plans WHERE plan_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        header("Location: index.php?status=error&msg=Failed to prepare delete statement");
        exit();
    }

    $stmt->bind_param("i", $plan_id);

    if ($stmt->execute()) {
        header("Location: index.php?status=success&msg=Plan deleted successfully");
    } else {
        header("Location: index.php?status=error&msg=Failed to delete plan");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php?status=error&msg=Invalid or missing plan ID");
    exit();
}
