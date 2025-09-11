<?php
include "../../database/db_connect.php";

if (isset($_GET['id'])) {
    $gym_id = intval($_GET['id']); // convert to integer for safety

    // Step 1: Get the image path first
    $result = $conn->prepare("SELECT image_url FROM gyms WHERE gym_id = ?");
    $result->bind_param("i", $gym_id);
    $result->execute();
    $result->bind_result($image_url);
    $result->fetch();
    $result->close();

    if ($image_url) {
        // Step 2: Delete the row from DB
        $stmt = $conn->prepare("DELETE FROM gyms WHERE gym_id = ?");
        $stmt->bind_param("i", $gym_id);

        if ($stmt->execute()) {
            // Step 3: Delete the image from the folder
            $filePath = realpath(__DIR__ . "/../../" . $image_url);
            if ($filePath && file_exists($filePath)) {
                unlink($filePath);
            }

            header("Location: index.php?status=success&msg=" . urlencode("Gym deleted successfully"));
            exit();
        } else {
            header("Location: index.php?status=error&msg=" . urlencode("Failed to delete gym"));
            exit();
        }

        $stmt->close();
    } else {
        header("Location: index.php?status=error&msg=" . urlencode("Gym not found"));
        exit();
    }
}

$conn->close();
?>
