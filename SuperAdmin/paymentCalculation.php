<?php
// -----------------------------
// Insert from customer_subscriptions
// -----------------------------
$sql1 = "
INSERT INTO gym_payouts (gym_id, payment_type, reference_id, amount, payout_status, created_at)
SELECT 
    cs.gym_id,
    'customer_subscription' AS payment_type,
    cs.subscription_id AS reference_id,
    cs.amount,
    'pending' AS payout_status,
    cs.created_at
FROM customer_subscriptions cs
WHERE cs.payment_status = 'paid'
AND NOT EXISTS (
    SELECT 1 FROM gym_payouts gp
    WHERE gp.reference_id = cs.subscription_id
    AND gp.payment_type = 'customer_subscription'
)
";
$conn->query($sql1);

// -----------------------------
// Insert from visitor_passes
// -----------------------------
$sql2 = "
INSERT INTO gym_payouts (gym_id, payment_type, reference_id, amount, payout_status, created_at)
SELECT 
    vp.gym_id,
    'visitor_pass' AS payment_type,
    vp.pass_id AS reference_id,
    vp.amount,
    'pending' AS payout_status,
    vp.created_at
FROM visitor_passes vp
WHERE vp.payment_status = 'paid'
AND NOT EXISTS (
    SELECT 1 FROM gym_payouts gp
    WHERE gp.reference_id = vp.pass_id
    AND gp.payment_type = 'visitor_pass'
)
";
$conn->query($sql2);

// -----------------------------
// Insert from trainer_bookings
// -----------------------------
$sql3 = "
INSERT INTO gym_payouts (gym_id, payment_type, reference_id, amount, payout_status, created_at)
SELECT 
    tb.gym_id,
    'trainer_booking' AS payment_type,
    tb.booking_id AS reference_id,
    tb.amount,
    'pending' AS payout_status,
    tb.created_at
FROM trainer_bookings tb
WHERE tb.payment_status = 'paid'
AND NOT EXISTS (
    SELECT 1 FROM gym_payouts gp
    WHERE gp.reference_id = tb.booking_id
    AND gp.payment_type = 'trainer_booking'
)
";
$conn->query($sql3);
