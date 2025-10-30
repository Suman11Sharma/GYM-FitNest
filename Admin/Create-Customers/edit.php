<?php
include "../../database/db_connect.php";

// Fetch customer data by ID
if (isset($_GET['id'])) {
    $customer_id = intval($_GET['id']);

    $sql = "SELECT * FROM customer WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();

    if (!$customer) {
        echo "<script>alert('Customer not found'); window.location='index.php';</script>";
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
                <h4 class="mb-0">Edit Customer</h4>
                <a href="index.php" class="btn btn-light btn-sm border ms-3" title="Back to Customer Table">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <div class="card-body p-4">
                <form action="update.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <input type="hidden" name="customer_id" value="<?= htmlspecialchars($customer['customer_id']) ?>">

                    <!-- Gym ID -->
                    <div class="mb-3">
                        <label for="gym_id" class="form-label">Gym ID</label>
                        <input type="text" class="form-control" id="gym_id" name="gym_id"
                            value="<?= htmlspecialchars($customer['gym_id']) ?>" required>
                        <div class="invalid-feedback">Please enter Gym ID.</div>
                    </div>

                    <!-- Full Name -->
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name"
                            value="<?= htmlspecialchars($customer['full_name']) ?>" required>
                        <div class="invalid-feedback">Please enter full name.</div>
                    </div>

                    <!-- Gender -->
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="" disabled>-- Select Gender --</option>
                            <option value="male" <?= ($customer['gender'] == 'male') ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?= ($customer['gender'] == 'female') ? 'selected' : '' ?>>Female</option>
                            <option value="other" <?= ($customer['gender'] == 'other') ? 'selected' : '' ?>>Other</option>
                        </select>
                        <div class="invalid-feedback">Please select gender.</div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?= htmlspecialchars($customer['email']) ?>" required>
                        <div class="invalid-feedback">Please enter a valid email.</div>
                    </div>

                    <!-- Phone -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone"
                            value="<?= htmlspecialchars($customer['phone']) ?>" pattern="^[0-9]{7,15}$" required>
                        <div class="invalid-feedback">Enter a valid phone number (7–15 digits).</div>
                    </div>

                    <!-- Address -->
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address"
                            value="<?= htmlspecialchars($customer['address']) ?>" required>
                        <div class="invalid-feedback">Please enter address.</div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Leave blank to keep current password" minlength="6">
                            <span class="input-group-text" style="cursor:pointer;"
                                onclick="togglePassword('password', 'togglePasswordIcon1')">
                                <i class="fas fa-eye" id="togglePasswordIcon1"></i>
                            </span>
                        </div>
                        <div class="invalid-feedback">Password must be at least 6 characters long.</div>
                    </div>

                    <!-- Date of Birth -->
                    <div class="mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                            value="<?= htmlspecialchars($customer['date_of_birth']) ?>" required>
                        <div class="invalid-feedback">Please select date of birth.</div>
                    </div>

                    <!-- Profile Image -->
                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Profile Image</label>
                        <?php if (!empty($customer['profile_image'])): ?>
                            <div class="mb-2">
                                <img src="../../uploads/customers/<?= htmlspecialchars($customer['profile_image']) ?>" alt="Profile"
                                    class="rounded" width="100" height="100" style="object-fit:cover;">
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                        <div class="invalid-feedback">Please upload a valid image file.</div>
                    </div>

                    <!-- Join Date -->
                    <div class="mb-3">
                        <label for="join_date" class="form-label">Join Date</label>
                        <input type="date" class="form-control" id="join_date" name="join_date"
                            value="<?= htmlspecialchars($customer['join_date']) ?>" required>
                        <div class="invalid-feedback">Please select join date.</div>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="" disabled>-- Select Status --</option>
                            <option value="active" <?= ($customer['status'] == 'active') ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= ($customer['status'] == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                        </select>
                        <div class="invalid-feedback">Please select status.</div>
                    </div>

                    <!-- Buttons -->
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

        // Toggle password visibility
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