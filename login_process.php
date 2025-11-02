<?php
session_start();
include "database/db_connect.php"; // adjust path if needed

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // --- Step 1: Check in users table (superadmin/admin/trainer via users table) ---
    $stmt = $conn->prepare("SELECT user_id, gym_id, name, email, password, role FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['gym_id'] = $user['gym_id'] ?? null;
            $_SESSION['role'] = $user['role'];

            switch ($user['role']) {
                case 'superadmin':
                    header("Location: SuperAdmin/superAdminPage.php?status=success&msg=" . urlencode("Welcome " . $user['name'] . "!"));
                    exit;
                case 'admin':
                    header("Location: Admin/adminPage.php?status=success&msg=" . urlencode("Welcome " . $user['name'] . "!"));
                    exit;
                case 'trainer':
                    header("Location: Trainer/trainer.php?status=success&msg=" . urlencode("Welcome " . $user['name'] . "!"));
                    exit;
                default:
                    header("Location: login.php?status=error&msg=" . urlencode("Invalid role detected."));
                    exit;
            }
        }
    }

    // --- Step 2: Check in trainers table (separate trainer accounts) ---
    $stmt = $conn->prepare("SELECT trainer_id, gym_id, name, email, password, specialization, rate_per_session, status 
                            FROM trainers WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $trainer = $result->fetch_assoc();

        // ✅ Only allow active trainers
        if ($trainer['status'] !== 'active') {
            header("Location: login.php?status=error&msg=" . urlencode("Your account is not active. Please contact your gym admin."));
            exit;
        }

        // ✅ Password check (assuming you’ve hashed trainer passwords with password_hash)
        if (password_verify($password, $trainer['password'])) {
            $_SESSION['trainer_id'] = $trainer['trainer_id'];
            $_SESSION['gym_id'] = $trainer['gym_id'];
            $_SESSION['name'] = $trainer['name'];
            $_SESSION['email'] = $trainer['email'];
            $_SESSION['specialization'] = $trainer['specialization'];
            $_SESSION['rate_per_session'] = $trainer['rate_per_session'];
            $_SESSION['role'] = 'trainer';

            header("Location: Trainer/trainerPage.php?status=success&msg=" . urlencode("Welcome Trainer " . $trainer['name'] . "!"));
            exit;
        }
    }

    // --- Step 3: Check in customers table ---
    $stmt = $conn->prepare("SELECT customer_id, gym_id, full_name, email, password FROM customers WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $customer = $result->fetch_assoc();

        if (password_verify($password, $customer['password'])) {
            $_SESSION['customer_id'] = $customer['customer_id'];
            $_SESSION['fullname'] = $customer['full_name'];
            $_SESSION['email'] = $customer['email'];
            $_SESSION['gym_id'] = $customer['gym_id'] ?? null;
            $_SESSION['role'] = 'customer';

            header("Location: Customer/customerPage.php?status=success&msg=" . urlencode("Welcome " . $customer['full_name'] . "!"));
            exit;
        }
    }

    // --- Step 4: No match found ---
    header("Location: login.php?status=error&msg=" . urlencode("Email or password is incorrect."));
    exit;
}
