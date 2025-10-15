<?php
include "../../database/db_connect.php";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];
    $gym_id = isset($_POST['gym_id']) && $_POST['gym_id'] !== '' ? $_POST['gym_id'] : null;
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // ✅ Secure password

    // Prepare the insert query
    $sql = "INSERT INTO users (role, gym_id, name, email, phone, password, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
    $stmt = $conn->prepare($sql);

    // Bind parameters (gym_id may be null)
    $stmt->bind_param("sissss", $role, $gym_id, $name, $email, $phone, $password);

    if ($stmt->execute()) {
        header("Location: index.php?status=success&msg=User+created+successfully");
        exit();
    } else {
        header("Location: index.php?status=error&msg=Failed+to+create+user");
        exit();
    }
}
?>
<?php
require("../sidelayout.php"); ?>

<!-- Your existing form — unchanged -->
<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">create User</h4>
                <a href="index.php" class="btn btn-light btn-sm border ms-3" title="Back to Create Users Table">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <div class="card-body">
                <form action="create.php" method="POST" class="needs-validation" novalidate>

                    <!-- Role (Dropdown) -->
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="" disabled selected>-- Select Role --</option>
                            <option value="superadmin">Superadmin</option>
                            <option value="admin">Admin</option>
                        </select>
                        <div class="invalid-feedback">Please select a role.</div>
                    </div>

                    <!-- Gym ID (only for Admin) -->
                    <div class="mb-3 d-none" id="gymIdWrapper">
                        <label for="gym_id" class="form-label">Gym ID</label>
                        <input type="text" class="form-control" id="gym_id" name="gym_id">
                        <div class="invalid-feedback">Please enter Gym ID.</div>
                    </div>

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback">Please enter full name.</div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">Please enter a valid email.</div>
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone"
                            pattern="^[0-9]{7,15}$" required>
                        <div class="invalid-feedback">Enter a valid phone number (7–15 digits).</div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" minlength="6" required>
                            <span class="input-group-text" style="cursor:pointer;" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="togglePasswordIcon1"></i>
                            </span>
                        </div>
                        <div class="invalid-feedback">Password must be at least 6 characters long.</div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="6" required>
                            <span class="input-group-text" style="cursor:pointer;" onclick="togglePassword('confirm_password')">
                                <i class="fas fa-eye" id="togglePasswordIcon2"></i>
                            </span>
                        </div>
                        <div class="invalid-feedback">Passwords do not match.</div>
                    </div>

                    <!-- Submit -->
                    <div class="text-center">
                        <button type="submit" class="btn-our px-5 py-2">Submit</button>
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
        // Toggle show/hide password
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const icon = fieldId === 'password' ? document.getElementById('togglePasswordIcon1') : document.getElementById('togglePasswordIcon2');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Confirm password validation
        const form = document.querySelector('.needs-validation');
        form.addEventListener('submit', function(event) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            if (password !== confirmPassword) {
                event.preventDefault();
                event.stopPropagation();
                document.getElementById('confirm_password').classList.add('is-invalid');
            } else {
                document.getElementById('confirm_password').classList.remove('is-invalid');
            }
        });
    </script>