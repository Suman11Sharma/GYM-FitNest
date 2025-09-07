<?php
include "../../database/db_connect.php";

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $gym_id = (int)$_GET['id'];

    // Optional: check if the gym exists
    $checkSql = "SELECT * FROM gyms WHERE gym_id = $gym_id";
    $checkResult = mysqli_query($conn, $checkSql);
    if (mysqli_num_rows($checkResult) > 0) {
        // Delete the gym
        $deleteSql = "DELETE FROM gyms WHERE gym_id = $gym_id";
        if (mysqli_query($conn, $deleteSql)) {
            // Successfully deleted, redirect back to list
            header("Location: index.php?msg=deleted");
            exit();
        } else {
            echo "Error deleting gym: " . mysqli_error($conn);
        }
    } else {
        echo "Gym not found.";
    }
} else {
    echo "Invalid gym ID.";
}
