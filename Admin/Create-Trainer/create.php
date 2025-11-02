<?php
include "../../database/db_connect.php";
session_start();
$gym_id = $_SESSION['gym_id'] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $gym_id = $_SESSION['gym_id'] ?? null;
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $specialization = trim($_POST['specialization']);
    $rate = trim($_POST['rate_per_session']);
    $status = $_POST['status'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (!$gym_id) {
        header("Location: create.php?status=error&msg=" . urlencode("Gym ID not found in session."));
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO trainers (gym_id, name, email, phone, specialization, rate_per_session, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("issssds", $gym_id, $name, $email, $phone, $specialization, $rate, $status);

    if ($stmt->execute()) {
        header("Location: index.php?status=success&msg=" . urlencode("Trainer added successfully."));
        exit;
    } else {
        header("Location: create.php?status=error&msg=" . urlencode("Failed to add trainer."));
        exit;
    }
}
require("../sidelayout.php");
?>
<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Create Trainer</h4>
                <a href="index.php" class="btn btn-light btn-sm border ms-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <div class="card-body p-4">
                <form action="create.php" method="POST" class="needs-validation" novalidate>

                    <!-- Gym ID (Hidden) -->
                    <input type="hidden" name="gym_id" value="<?php echo htmlspecialchars($gym_id); ?>">

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Trainer Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <!-- Phone -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="phone" name="phone" pattern="^[0-9]{7,15}$" required>
                        <div class="invalid-feedback">Enter a valid phone number (7–15 digits).</div>
                    </div>

                    <!-- Specialization -->
                    <div class="mb-3">
                        <label for="specialization" class="form-label">Specialization</label>
                        <input type="text" class="form-control" id="specialization" name="specialization">
                    </div>

                    <!-- Rate Per Session -->
                    <div class="mb-3">
                        <label for="rate_per_session" class="form-label">Rate Per Session (Rs.)</label>
                        <input type="number" class="form-control" id="rate_per_session" name="rate_per_session" required>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="" disabled selected>-- Select Status --</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <!-- Password -->
                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" minlength="6" required>
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3 position-relative">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="6" required>
                            <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-5 py-2">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php require("../assets/link.php"); ?>

    <script>
        // Form Validation + Password Match
        (() => {
            'use strict';
            const form = document.querySelector('.needs-validation');
            form.addEventListener('submit', event => {
                const password = document.getElementById('password');
                const confirm = document.getElementById('confirm_password');
                if (password.value !== confirm.value) {
                    event.preventDefault();
                    event.stopPropagation();
                    alert('Passwords do not match!');
                    return false;
                }
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        })();

        // Toggle Password Visibility
        document.getElementById('togglePassword').addEventListener('click', () => {
            const pwd = document.getElementById('password');
            const icon = document.querySelector('#togglePassword i');
            pwd.type = pwd.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', () => {
            const pwd = document.getElementById('confirm_password');
            const icon = document.querySelector('#toggleConfirmPassword i');
            pwd.type = pwd.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    </script>
</div>