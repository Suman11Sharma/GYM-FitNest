<?php
require("../sidelayout.php");

// Example: fetch user from DB (replace with actual DB code)
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// TODO: Replace this with database fetch using $id
$user = [
    "id" => $id,
    "name" => "John Doe",
    "email" => "john@example.com",
    "phone" => "9800000000",
    "role" => "admin"
];
?>
<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Edit User</h4>
                <a href="index.php" class="btn btn-sm btn-outline-light">← Back</a>
            </div>
            <div class="card-body">
                <form action="update.php" method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        <div class="invalid-feedback">Please enter full name.</div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        <div class="invalid-feedback">Please enter a valid email.</div>
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone"
                            value="<?php echo htmlspecialchars($user['phone']); ?>"
                            pattern="^[0-9]{7,15}$" required>
                        <div class="invalid-feedback">Enter a valid phone number (7–15 digits).</div>
                    </div>

                    <!-- Role (Dropdown) -->
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="" disabled>-- Select Role --</option>
                            <option value="superadmin" <?php echo ($user['role'] === 'superadmin') ? 'selected' : ''; ?>>Superadmin</option>
                            <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                        </select>
                        <div class="invalid-feedback">Please select a role.</div>
                    </div>


                    <!-- Update -->
                    <div class="text-center">
                        <button type="submit" class=" btn-our px-5 py-2">Update</button>
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
    </script>
</div>