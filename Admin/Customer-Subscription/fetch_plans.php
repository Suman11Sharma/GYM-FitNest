<?php
include "../../database/db_connect.php";

$gym_id = intval($_GET['gym_id']);
$plans = [];
$result = mysqli_query($conn, "SELECT plan_id, plan_name, duration_days, amount FROM customer_plans WHERE gym_id = $gym_id AND status='active'");
while ($row = mysqli_fetch_assoc($result)) {
    $plans[] = $row;
}

header('Content-Type: application/json');
echo json_encode($plans);
