<?php
include "../database/admin_authentication.php";
include "../database/db_connect.php";

// Ensure customer is logged in
if (!isset($_SESSION['customer_id']) || !isset($_SESSION['gym_id'])) {
    header("Location: ../login.php");
    exit();
}

$customer_id = intval($_GET['customer_id'] ?? $_SESSION['customer_id']);
$gym_id = intval($_SESSION['gym_id']);

// Fetch customer info
$stmt = $conn->prepare("
    SELECT customer_id, full_name, gender, email, phone, address, date_of_birth, profile_image 
    FROM customers 
    WHERE customer_id=? AND gym_id=? LIMIT 1
");
$stmt->bind_param("ii", $customer_id, $gym_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("❌ Customer not found.");
}

$customer = $result->fetch_assoc();
$profile_image = !empty($customer['profile_image'])
    ? 'data:image/jpeg;base64,' . base64_encode($customer['profile_image'])
    : 'https://cdn-icons-png.flaticon.com/512/149/149071.png';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $gender = trim($_POST['gender']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $dob = trim($_POST['date_of_birth']);

    // Handle image upload
    $profile_blob = null;
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['size'] > 0) {
        $profile_blob = file_get_contents($_FILES['profile_image']['tmp_name']);
    }

    if ($profile_blob) {
        $update_stmt = $conn->prepare("
            UPDATE customers 
            SET full_name=?, gender=?, email=?, phone=?, address=?, date_of_birth=?, profile_image=?, updated_at=NOW()
            WHERE customer_id=? AND gym_id=?
        ");
        $update_stmt->bind_param("ssssssbii", $full_name, $gender, $email, $phone, $address, $dob, $null, $customer_id, $gym_id);
        $update_stmt->send_long_data(6, $profile_blob);
    } else {
        $update_stmt = $conn->prepare("
            UPDATE customers 
            SET full_name=?, gender=?, email=?, phone=?, address=?, date_of_birth=?, updated_at=NOW()
            WHERE customer_id=? AND gym_id=?
        ");
        $update_stmt->bind_param("ssssssii", $full_name, $gender, $email, $phone, $address, $dob, $customer_id, $gym_id);
    }

    if ($update_stmt->execute()) {
        header("Location: customerPage.php?status=success&msg=Profile updated successfully");
        exit();
    } else {
        $error = "Failed to update profile. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Customer Info | FitNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Edit Profile</h4>
                <a href="dashboard.php" class="btn btn-light btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="text-center mb-3">
                        <img src="<?= $profile_image ?>" alt="Profile Image" class="rounded-circle" width="120" height="120" style="object-fit: cover;">
                    </div>

                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Profile Image (optional)</label>
                        <input type="file" class="form-control" name="profile_image" id="profile_image" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="full_name" id="full_name" required
                            value="<?= htmlspecialchars($customer['full_name']) ?>">
                    </div>

                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select" name="gender" id="gender" required>
                            <option value="">-- Select Gender --</option>
                            <option value="Male" <?= $customer['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= $customer['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                            <option value="Other" <?= $customer['gender'] === 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" required
                            value="<?= htmlspecialchars($customer['email']) ?>">
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone" id="phone" required
                            value="<?= htmlspecialchars($customer['phone']) ?>">
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" name="address" id="address" rows="2" required><?= htmlspecialchars($customer['address']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" name="date_of_birth" id="date_of_birth" required
                            value="<?= htmlspecialchars($customer['date_of_birth']) ?>">
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-5"><i class="fas fa-save me-1"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>