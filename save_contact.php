<?php
include "database/db_connect.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $email   = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $contact = mysqli_real_escape_string($conn, $_POST['contact'] ?? '');
    $subject = mysqli_real_escape_string($conn, $_POST['subject'] ?? '');
    $message = mysqli_real_escape_string($conn, $_POST['message'] ?? '');

    // Validation for required fields
    if (!$name || !$email || !$contact || !$subject || !$message) {
        die("❌ All fields are required.");
    }

    // Optional fields
    $gym_id = !empty($_POST['gym_id']) ? "'" . mysqli_real_escape_string($conn, $_POST['gym_id']) . "'" : "NULL";

    $sql = "INSERT INTO contact_queries 
            (gym_id, name, email, contact, subject, message)
            VALUES (
                $gym_id,
                '$name',
                '$email',
                '$contact',
                '$subject',
                '$message'
            )";

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?status=success&msg=" . urlencode("Your query has been submitted successfully!"));
        exit;
    } else {
        header("Location: index.php?status=error&msg=" . urlencode("Database error: " . mysqli_error($conn)));
        exit;
    }
}
