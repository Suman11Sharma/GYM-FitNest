<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// Fetch user data by ID
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo "<script>alert('User not found'); window.location='index.php';</script>";
        exit;
    }
} else {
    echo "<script>window.location='index.php';</script>";
    exit;
}
require("../sidelayout.php");

?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Edit Users</h4>
                <a href="index.php" class="btn btn-light btn-sm border ms-3" title="Back to Create Users Table">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <div class="card-body">
                <form action="update.php" method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']) ?>">

                    <!-- Role (Dropdown) -->
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="" disabled>-- Select Role --</option>
                            <option value="superadmin" <?= ($user['role'] == 'superadmin') ? 'selected' : '' ?>>Superadmin</option>
                            <option value="admin" <?= ($user['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                        </select>
                        <div class="invalid-feedback">Please select a role.</div>
                    </div>

                    <!-- Gym ID (only for Admin) -->
                    <div class="mb-3 <?= ($user['role'] !== 'admin') ? 'd-none' : '' ?>" id="gymIdWrapper">
                        <label for="gym_id" class="form-label">Gym ID</label>
                        <input type="text" class="form-control" id="gym_id" name="gym_id"
                            value="<?= htmlspecialchars($user['gym_id']) ?>">
                        <div class="invalid-feedback">Please enter Gym ID.</div>
                    </div>

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="<?= htmlspecialchars($user['name']) ?>" required>
                        <div class="invalid-feedback">Please enter full name.</div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?= htmlspecialchars($user['email']) ?>" required>
                        <div class="invalid-feedback">Please enter a valid email.</div>
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone"
                            value="<?= htmlspecialchars($user['phone']) ?>"
                            pattern="^[0-9]{7,15}$" required>
                        <div class="invalid-feedback">Enter a valid phone number (7–15 digits).</div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Leave blank to keep current password" minlength="6">
                            <span class="input-group-text" style="cursor:pointer;" onclick="togglePassword('password', 'togglePasswordIcon1')">
                                <i class="fas fa-eye" id="togglePasswordIcon1"></i>
                            </span>
                        </div>
                        <div class="invalid-feedback">Password must be at least 6 characters long.</div>
                    </div>

                    <!-- Submit -->
                    <div class="text-center">
                        <button type="submit" class="btn-our px-5 py-2">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php require("../assets/link.php"); ?>

    <script>
        // Bootstrap form validation
        (() => {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        // Show/hide Gym ID based on role
        document.getElementById('role').addEventListener('change', function() {
            const gymIdWrapper = document.getElementById('gymIdWrapper');
            const gymIdInput = document.getElementById('gym_id');
            if (this.value === 'admin') {
                gymIdWrapper.classList.remove('d-none');
                gymIdInput.setAttribute('required', 'required');
            } else {
                gymIdWrapper.classList.add('d-none');
                gymIdInput.removeAttribute('required');
                gymIdInput.value = '';
            }
        });

        function togglePassword(fieldId, iconId) {
            const passwordInput = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</div>