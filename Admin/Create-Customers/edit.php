<?php
include "../../database/db_connect.php";
session_start();

if (!isset($_SESSION['gym_id'])) {
    die("❌ Session expired. Please log in again.");
}
$gym_id = $_SESSION['gym_id'];

// Get customer ID
$customer_id = $_GET['id'] ?? null;
if (!$customer_id) {
    die("❌ Invalid customer ID.");
}

// Fetch existing customer
$stmt = $conn->prepare("SELECT * FROM customers WHERE customer_id = ? AND gym_id = ?");
$stmt->bind_param("ii", $customer_id, $gym_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

if (!$customer) {
    die("❌ Customer not found.");
}

// Handle form submission
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

    if (!$full_name || !$gender || !$email || !$phone || !$address) {
        die("❌ Please fill all required fields.");
    }

    // Hash password only if provided
    $hashedPassword = $customer['password'];
    if ($password || $cpassword) {
        if ($password !== $cpassword) {
            die("❌ Password and Confirm Password do not match.");
        }
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    }

    // ✅ Check if a new image was uploaded
    $new_image_uploaded = isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK;
    $image_data = null;
    if ($new_image_uploaded) {
        $file_tmp = $_FILES['profile_image']['tmp_name'];
        $file_size = $_FILES['profile_image']['size'];
        if ($file_size > 5 * 1024 * 1024) {
            header("Location: index.php?status=error&msg=" . urlencode("file size shouldnot exceed 5mb"));
            exit();
        }
        $image_data = file_get_contents($file_tmp);
    }

    if ($new_image_uploaded) {
        // ✅ If new image uploaded
        $sql = "UPDATE customers SET
                full_name = ?, gender = ?, email = ?, phone = ?, address = ?, 
                password = ?, date_of_birth = ?, status = ?, profile_image = ?
                WHERE customer_id = ? AND gym_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssssbii",  // 8 strings, 1 blob, 2 ints
            $full_name,
            $gender,
            $email,
            $phone,
            $address,
            $hashedPassword,
            $date_of_birth,
            $status,
            $null, // placeholder for blob
            $customer_id,
            $gym_id
        );

        $stmt->send_long_data(8, $image_data); // index 8 for blob
    } else {
        // ✅ If no new image uploaded, keep old image
        $sql = "UPDATE customers SET
                full_name = ?, gender = ?, email = ?, phone = ?, address = ?, 
                password = ?, date_of_birth = ?, status = ?
                WHERE customer_id = ? AND gym_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssssssii",
            $full_name,
            $gender,
            $email,
            $phone,
            $address,
            $hashedPassword,
            $date_of_birth,
            $status,
            $customer_id,
            $gym_id
        );
    }

    if ($stmt->execute()) {
        header("Location: index.php?status=success&msg=" . urlencode("Customer updated successfully!"));
        exit;
    } else {
        header("Location: index.php?status=error&msg=" . urlencode("Database error: " . $stmt->error));
    }
}
?>


<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Edit Customer</h4>
                <a href="index.php" class="btn btn-light btn-sm border ms-3"><i class="fas fa-arrow-left"></i></a>
            </div>
            <div class="card-body p-4">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="full_name" value="<?= htmlspecialchars($customer['full_name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select class="form-select" name="gender" required>
                            <option value="male" <?= $customer['gender'] == 'male' ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?= $customer['gender'] == 'female' ? 'selected' : '' ?>>Female</option>
                            <option value="other" <?= $customer['gender'] == 'other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($customer['email']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="tel" class="form-control" name="phone" value="<?= htmlspecialchars($customer['phone']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="address" value="<?= htmlspecialchars($customer['address']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Leave blank to keep current password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="cpassword" placeholder="Leave blank to keep current password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" name="date_of_birth" value="<?= htmlspecialchars($customer['date_of_birth']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Profile Image</label>
                        <input type="file" class="form-control" name="profile_image" accept="image/*">
                        <?php if (!empty($customer['profile_image'])): ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($customer['profile_image']) ?>" alt="Profile" width="80" class="mt-2 rounded-circle">
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" required>
                            <option value="active" <?= $customer['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $customer['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success px-5">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <?php require("../assets/link.php"); ?>
</div>