<?php
include "database/db_connect.php"; // Adjust path

$today = date('Y-m-d');

// ✅ Array of tables and their end date column
$tables = [
    'ads' => 'end_date',
    'customer_subscriptions' => 'end_date',
    'gym_subscriptions' => 'end_date',
    'paid_ads' => 'end_date'
];

foreach ($tables as $table => $endDateColumn) {
    $sql = "UPDATE `$table` 
            SET status='inactive' 
            WHERE status='active' 
              AND `$endDateColumn` < '$today'";

    if ($conn->query($sql)) {
        echo date('Y-m-d H:i:s') . " - Table `$table` updated successfully.\n";
    } else {
        echo date('Y-m-d H:i:s') . " - Error updating `$table`: " . $conn->error . "\n";
    }
}

$conn->close();
