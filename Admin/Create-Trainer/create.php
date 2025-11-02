<?php require("../sidelayout.php"); ?>
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
                <form action="store.php" method="POST" class="needs-validation" novalidate>

                    <!-- Trainer ID  -->
                    <div class="mb-3">
                        <label for="trainer_id" class="form-label">Trainer ID </label>
                        <input type="text" class="form-control" id="trainer_id" name="trainer_id" readonly>
                    </div>

                    <!-- Gym ID -->
                    <div class="mb-3">
                        <label for="gym_id" class="form-label">Gym ID</label>
                        <input type="text" class="form-control" id="gym_id" name="gym_id" required>
                    </div>

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Trainer Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <!-- Phone -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" pattern="^[0-9]{7,15}$" required>
                        <div class="invalid-feedback">Enter a valid phone number (7–15 digits).</div>
                    </div>

                    <!-- Specialization -->
                    <div class="mb-3">
                        <label for="specialization" class="form-label">Specialization</label>
                        <input type="text" class="form-control" id="specialization" name="specialization" required>
                    </div>

                    <!-- Rate Per Session -->
                    <div class="mb-3">
                        <label for="rate_per_session" class="form-label">Rate Per Session</label>
                        <input type="number" class="form-control" id="rate_per_session" name="rate_per_session" required>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" minlength="6" required>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="6" required>
                    </div>

                    <!-- Buttons -->

                    <div class="text-center">
                        <button type="submit" class="btn-our px-5 py-2">Submit</button>
                    </div>

                </form>
            </div>
        </div>
    </main>
    <?php require("../assets/link.php"); ?>
    <!-- Client-side Password Validation -->
    <script>
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
    </script>
</div>