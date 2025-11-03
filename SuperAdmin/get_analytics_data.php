<?php
include("../database/db_connect.php");
header("Content-Type: application/json");

$year = $_GET['year'] ?? date("Y");
$currentMonth = date("m");

$income = [
    "customer_subscriptions" => 0,
    "gym_subscriptions" => 0,
    "paid_ads" => 0,
    "trainer_bookings" => 0,
    "visitor_passes" => 0
];

// Helper function for normal tables
function sumAmount($conn, $table, $month, $year, $dateField = 'created_at')
{
    $sql = "SELECT SUM(amount) AS total FROM `$table` 
            WHERE MONTH($dateField)=? AND YEAR($dateField)=? AND payment_status='paid'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $month, $year);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    return (float)($res['total'] ?? 0);
}

// ✅ Customer & Gym subscriptions
$income['customer_subscriptions'] = sumAmount($conn, 'customer_subscriptions', $currentMonth, $year);
$income['gym_subscriptions'] = sumAmount($conn, 'gym_subscriptions', $currentMonth, $year);

// ✅ Paid ads (use start_date or created_at)
$sqlAds = "SELECT SUM(amount) AS total FROM paid_ads 
           WHERE MONTH(start_date)=? AND YEAR(start_date)=? AND payment_status='paid'";
$stmt = $conn->prepare($sqlAds);
$stmt->bind_param("ii", $currentMonth, $year);
$stmt->execute();
$income['paid_ads'] = (float)($stmt->get_result()->fetch_assoc()['total'] ?? 0);

// ✅ Trainer bookings
$income['trainer_bookings'] = sumAmount($conn, 'trainer_bookings', $currentMonth, $year, 'session_date');

// ✅ Visitor passes (use payment_status = 'paid', check created_at)
$sqlVisitor = "SELECT SUM(amount) AS total FROM visitor_passes 
               WHERE MONTH(created_at)=? AND YEAR(created_at)=? AND payment_status='paid'";
$stmt = $conn->prepare($sqlVisitor);
$stmt->bind_param("ii", $currentMonth, $year);
$stmt->execute();
$income['visitor_passes'] = (float)($stmt->get_result()->fetch_assoc()['total'] ?? 0);

// --- Revenue by Plan Type ---
$plan_labels = [];
$plan_counts = [];
$planSql = "SELECT sp.plan_name, COUNT(gs.plan_name) as count 
            FROM saas_plans sp 
            LEFT JOIN gym_subscriptions gs ON gs.plan_name = sp.plan_name
            GROUP BY sp.plan_name";
$result = $conn->query($planSql);
while ($row = $result->fetch_assoc()) {
    $plan_labels[] = $row['plan_name'];
    $plan_counts[] = (int)$row['count'];
}

// --- Monthly Revenue (selected year) ---
$monthly_revenue = array_fill(0, 12, 0);
$tables = [
    ['table' => 'customer_subscriptions', 'date' => 'created_at'],
    ['table' => 'gym_subscriptions', 'date' => 'created_at'],
    ['table' => 'paid_ads', 'date' => 'start_date'],
    ['table' => 'trainer_bookings', 'date' => 'session_date'],
    ['table' => 'visitor_passes', 'date' => 'created_at']
];

foreach ($tables as $t) {
    $sql = "SELECT MONTH({$t['date']}) as m, SUM(amount) as total 
            FROM `{$t['table']}` 
            WHERE YEAR({$t['date']})=? AND payment_status='paid' 
            GROUP BY MONTH({$t['date']})";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $year);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc()) {
        $monthly_revenue[$r['m'] - 1] += (float)$r['total'];
    }
}

echo json_encode([
    "income" => $income,
    "plan_labels" => $plan_labels,
    "plan_counts" => $plan_counts,
    "monthly_revenue" => $monthly_revenue
]);
