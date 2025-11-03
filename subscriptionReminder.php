<?php
include "database/db_connect.php";
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Get today's date and 3 days later
$today = date('Y-m-d');
$threshold_date = date('Y-m-d', strtotime('+3 days'));

// Fetch customers whose subscription ends within 3 days and are still active
$sql = "SELECT cs.subscription_id, cs.end_date, cs.user_id, cs.gym_id, cs.amount,
               c.full_name AS customer_name, c.email AS customer_email,
               g.name AS gym_name, g.email AS gym_email
        FROM customer_subscriptions cs
        INNER JOIN customers c ON cs.user_id = c.customer_id
        INNER JOIN gyms g ON cs.gym_id = g.gym_id
        WHERE cs.status = 'active' AND cs.end_date BETWEEN '$today' AND '$threshold_date'";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $mail = new PHPMailer(true);
        try {
            // PHPMailer SMTP settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'sumanpoudelsharma@gmail.com';
            $mail->Password   = 'rwfobnfellifzinc';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('sumanpoudelsharma@gmail.com', 'GYM FitNest');

            // Send to customer
            $mail->addAddress($row['customer_email'], $row['customer_name']);
            $mail->Subject = "Subscription Expiring Soon!";
            $mail->Body = "Hello {$row['customer_name']},\n\nYour subscription for {$row['gym_name']} will expire on {$row['end_date']}.\nPlease renew it to continue enjoying our services.\n\nThank you.";

            $mail->send();

            // Optional: log success
            echo "Reminder sent to {$row['customer_email']}\n";
        } catch (Exception $e) {
            echo "Mailer Error for {$row['customer_email']}: {$mail->ErrorInfo}\n";
        }

        // Clear all recipients for next iteration
        $mail->clearAddresses();
    }
} else {
    echo "No subscriptions expiring within 3 days.\n";
}

$conn->close();
