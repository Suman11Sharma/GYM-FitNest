<?php
include "database/db_connect.php";
var_dump($_POST['gym_id']);
exit();


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $gym_id = intval($_POST['gym_id']); // comes from the modal hidden input
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $time_from = $_POST['time_from'];
    $time_to = $_POST['time_to'];
    $amount = floatval($_POST['amount']); // ensure float
    $payment_method = $_POST['payment_method'];

    $payment_status = ($payment_method === 'Pay Now') ? 'Pending' : 'Unpaid';
    $transaction_id = null; // can be null
    $created_at = date('Y-m-d H:i:s');
    $updated_at = $created_at;

    $sql = "INSERT INTO visitor_passes 
            (gym_id, name, contact, email, time_from, time_to, amount, payment_method, payment_status, transaction_id, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Types: i = integer, s = string, d = double, s = string, s = string, s = string, d = double, s = string, s = string, s = string, s = string, s = string
    $stmt->bind_param("isssssdsssss", $gym_id, $name, $contact, $email, $time_from, $time_to, $amount, $payment_method, $payment_status, $transaction_id, $created_at, $updated_at);

    if ($stmt->execute()) {
        header("Location: index.php?status=success");
        exit();
    } else {
        header("Location: index.php?status=error");
        exit();
    }
}
