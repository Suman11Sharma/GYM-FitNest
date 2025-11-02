<?php
session_start();

// ✅ Case 1: SuperAdmin — only user_id and role stored
if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'superadmin') {
    // Super admin authenticated ✅
    return;
}

// ✅ Case 2: Other users (Admin / Trainer) — must have gym_id too
if (
    isset($_SESSION['user_id']) &&
    isset($_SESSION['role']) &&
    in_array($_SESSION['role'], ['admin', 'trainer']) &&
    isset($_SESSION['gym_id'])
) {
    // Admin or Trainer authenticated ✅
    return;
}

// ✅ Case 3: Customer — must have customer_id + gym_id
if (isset($_SESSION['customer_id']) && isset($_SESSION['gym_id'])) {
    // Customer authenticated ✅
    return;
}
if (isset($_SESSION['trainer_id']) && isset($_SESSION['gym_id'])) {
    // Customer authenticated ✅
    return;
}

// ❌ If none of the above matched — redirect to login
header("Location: ../login.php?status=error&msg=" . urlencode("Please login to continue."));
exit();
