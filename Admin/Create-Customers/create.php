<?php
include "../../database/db_connect.php";
session_start();

if (!isset($_SESSION['gym_id'])) {
    die("❌ Session expired. Please log in again.");
}
$gym_id = $_SESSION['gym_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';
    $cpassword = $_POST['cpassword'] ?? '';
    $date_of_birth = $_POST['date_of_birth'] ?? null;
    $status = $_POST['status'] ?? 'active';
    $join_date = date('Y-m-d');

    if (!$full_name || !$gender || !$email || !$phone || !$address || !$password || !$cpassword) {
        die("❌ Please fill all required fields.");
    }

    if ($password !== $cpassword) {
        die("❌ Password and Confirm Password do not match.");
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // ✅ Image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp = $_FILES['profile_image']['tmp_name'];
        $image_data = file_get_contents($image_tmp);
    } else {
        die("❌ Please upload a valid image file.");
    }

    $sql = "INSERT INTO customers 
        (gym_id, full_name, gender, email, phone, address, password, date_of_birth, profile_image, join_date, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    // bind params (temporary NULL for blob)
    $stmt->bind_param(
        "issssssssss",
        $gym_id,
        $full_name,
        $gender,
        $email,
        $phone,
        $address,
        $hashedPassword,
        $date_of_birth,
        $image_data, // placeholder for blob
        $join_date,
        $status
    );
    $stmt->send_long_data(2, $image_data);

    if ($stmt->execute()) {
        header("Location: index.php?status=success&msg=" . urlencode("Customer created successfully!"));
        exit;
    } else {
        die("❌ Database error: " . $stmt->error);
    }
}
require("../sidelayout.php");
?>


<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Create Customer</h4>
                <a href="index.php" class="btn btn-light btn-sm border ms-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <div class="card-body p-4">


                <form action="" method="POST" enctype="multipart/form-data">

                    <div class="mb-3">
                        <input hidden type="text" class="form-control" name="gym_id" value="<?= htmlspecialchars($gym_id) ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="full_name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select class="form-select" name="gender" required>
                            <option value="" disabled selected>-- Select Gender --</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="tel" class="form-control" name="phone" pattern="^[0-9]{7,15}$" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="address" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" minlength="6" required>
                            <span class="input-group-text" onclick="togglePassword('password')"><i class="fas fa-eye"></i></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="cpassword" name="cpassword" minlength="6" required>
                            <span class="input-group-text" onclick="togglePassword('cpassword')"><i class="fas fa-eye"></i></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" name="date_of_birth" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Profile Image</label>
                        <input type="file" class="form-control" name="profile_image" accept="image/*" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Join Date</label>
                        <input type="text" class="form-control" name="join_date" value="<?= date('Y-m-d') ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" required>
                            <option value="" disabled selected>-- Select Status --</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn-our px-5 py-2">Submit</button>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <?php require("../assets/link.php"); ?>

    <script>
        // ✅ Password eye toggle
        function togglePassword(id) {
            const field = document.getElementById(id);
            const icon = event.currentTarget.querySelector("i");
            if (field.type === "password") {
                field.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                field.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }
    </script>
</div>