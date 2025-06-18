<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <div class="container py-5">
        <h2 class="mb-4 text-center">Registration Form</h2>
        <form action="#" method="POST" enctype="multipart/form-data">

            <!-- Company Name -->
            <div class="mb-3">
                <label class="form-label">Company Name</label>
                <input type="text" class="form-control" name="companyName" required>
            </div>

            <!-- Address Section -->
            <div class="mb-3">
                <label class="form-label">Address</label>
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="city" placeholder="City" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="state" placeholder="State/Province" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="zipcode" placeholder="Zip Code" required>
                    </div>
                    <div class="col-md-8 mt-2">
                        <input type="text" class="form-control" name="fullAddress" placeholder="Full Address" required>
                    </div>
                    <div class="col-md-4 mt-2">
                        <input type="url" class="form-control" name="mapLink" placeholder="Google Map Link">
                    </div>
                </div>
            </div>

            <!-- Contact Number -->
            <div class="mb-3">
                <label class="form-label">Contact Number</label>
                <input type="tel" class="form-control" name="contactNumber" required>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>

            <!-- Company Description -->
            <div class="mb-3">
                <label class="form-label">Description of Company</label>
                <textarea class="form-control" name="companyDescription" rows="3" required></textarea>
            </div>

            <!-- Admin Username -->
            <div class="mb-3">
                <label class="form-label">Admin Username</label>
                <input type="text" class="form-control" name="adminUsername" required>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password', this)">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('confirmPassword', this)">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
                <div id="passwordError" class="text-danger mt-1" style="display: none;">Passwords do not match.</div>
            </div>


            <!-- Image Upload -->
            <div class="mb-3">
                <label class="form-label">Upload Company Logo/Image</label>
                <input type="file" class="form-control" name="companyImage" accept="image/*" required>
            </div>
            <!-- Submit -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-5 py-2">Submit</button>
            </div>
        </form>
    </div>

    <!-- JavaScript Section -->
    <script>
        function togglePassword(fieldId, btn) {
            const input = document.getElementById(fieldId);
            const icon = btn.querySelector('i');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                input.type = "password";
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        }

    </script>



    <?php require("../assets/link.php"); ?>