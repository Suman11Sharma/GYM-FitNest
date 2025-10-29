<?php
session_start();
include "database/db_connect.php"; // adjust path if needed

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // --- Step 1: Check in users table ---
    $stmt = $conn->prepare("SELECT user_id, gym_id, name, email, password, role FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // ✅ Password matched in users table
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['gym_id'] = $user['gym_id'] ?? null;
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
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

    // --- Step 2: Check in customers table ---
    $stmt = $conn->prepare("SELECT customer_id, gym_id, full_name, email, password FROM customers WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $customer = $result->fetch_assoc();

        if (password_verify($password, $customer['password'])) {
            // ✅ Password matched in customers table
            $_SESSION['customer_id'] = $customer['customer_id'];
            $_SESSION['fullname'] = $customer['fullname'];
            $_SESSION['email'] = $customer['email'];
            $_SESSION['gym_id'] = $customer['gym_id'] ?? null;

            header("Location: Customer/customerPage.php?status=success&msg=" . urlencode("Welcome " . $customer['fullname'] . "!"));
            exit;
        }
    }

    // --- Step 3: If no match found in either table ---
    header("Location: login.php?status=error&msg=" . urlencode("Email or password is incorrect."));
    exit;
}
