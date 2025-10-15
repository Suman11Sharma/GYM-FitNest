<?php
include "../../database/db_connect.php";

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = (int)$_GET['id'];

    // Prepare & execute delete statement
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        $status = "success";
        $msg = "User has been deleted successfully.";
    } else {
        $status = "error";
        $msg = "Failed to delete the user. Please try again.";
    }

    $stmt->close();
} else {
    $status = "error";
    $msg = "Invalid user ID.";
}

// Redirect back to index with status + message
header("Location: index.php?status=$status&msg=" . urlencode($msg));
exit;
