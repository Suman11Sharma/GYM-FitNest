<?php
include "../../database/db_connect.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?status=error&msg=Invalid trainer ID");
    exit;
}

$id = intval($_GET['id']);
$query = "SELECT * FROM trainers WHERE trainer_id = $id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: index.php?status=error&msg=Trainer not found");
    exit;
}

$trainer = mysqli_fetch_assoc($result);
require("../sidelayout.php");
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container mt-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Edit Trainer</h4>
                    <a href="index.php" class="btn btn-light btn-sm"><i class="fas fa-arrow-left"></i></a>
                </div>

                <div class="card-body">
                    <form action="update.php" method="POST" id="editForm">
                        <input type="hidden" name="trainer_id" value="<?= htmlspecialchars($trainer['trainer_id']); ?>">

                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($trainer['name']); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($trainer['email']); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" required value="<?= htmlspecialchars($trainer['phone']); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Specialization <span class="text-danger">*</span></label>
                            <input type="text" name="specialization" class="form-control" required value="<?= htmlspecialchars($trainer['specialization']); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Rate per Session (Rs) <span class="text-danger">*</span></label>
                            <input type="number" name="rate_per_session" class="form-control" required value="<?= htmlspecialchars($trainer['rate_per_session']); ?>">
                        </div>

                        <!-- Password Fields (Optional) -->
                        <div class="mb-3 position-relative">
                            <label class="form-label">New Password (Leave blank to keep current)</label>
                            <div class="input-group">
                                <input type="password" id="password" name="password" class="form-control" minlength="6">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3 position-relative">
                            <label class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" minlength="6">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('confirm_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active" <?= $trainer['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?= $trainer['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="index.php" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Trainer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function togglePassword(id) {
                const input = document.getElementById(id);
                input.type = input.type === "password" ? "text" : "password";
            }

            document.getElementById("editForm").addEventListener("submit", function(e) {
                const pass = document.getElementById("password").value;
                const confirm = document.getElementById("confirm_password").value;
                if (pass && pass !== confirm) {
                    e.preventDefault();
                    alert("Passwords do not match!");
                }
            });
        </script>
    </main>

    <?php require("../assets/link.php"); ?>
</div>