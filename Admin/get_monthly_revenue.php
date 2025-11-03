<?php
include "../database/user_authentication.php";
include "../database/db_connect.php";

$gym_id = $_SESSION['gym_id'] ?? 0;
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

$monthlyRevenue = array_fill(0, 12, 0);

// Subscriptions
$subQuery = "SELECT MONTH(start_date) AS month, SUM(amount) AS total
             FROM customer_subscriptions
             WHERE gym_id = $gym_id AND YEAR(start_date) = $year
             GROUP BY month";
$subResult = mysqli_query($conn, $subQuery);
if ($subResult) while ($row = mysqli_fetch_assoc($subResult)) $monthlyRevenue[(int)$row['month'] - 1] += (float)$row['total'];

// Visitor Passes
$passQuery = "SELECT MONTH(created_at) AS month, SUM(amount) AS total
              FROM visitor_passes
              WHERE gym_id = $gym_id AND YEAR(created_at) = $year
              GROUP BY month";
$passResult = mysqli_query($conn, $passQuery);
if ($passResult) while ($row = mysqli_fetch_assoc($passResult)) $monthlyRevenue[(int)$row['month'] - 1] += (float)$row['total'];

// Trainer Bookings
$trainerQuery = "SELECT MONTH(session_date) AS month, SUM(amount) AS total
                 FROM trainer_bookings
                 WHERE gym_id = $gym_id AND YEAR(session_date) = $year
                 GROUP BY month";
$trainerResult = mysqli_query($conn, $trainerQuery);
if ($trainerResult) while ($row = mysqli_fetch_assoc($trainerResult)) $monthlyRevenue[(int)$row['month'] - 1] += (float)$row['total'];

header('Content-Type: application/json');
echo json_encode($monthlyRevenue);
