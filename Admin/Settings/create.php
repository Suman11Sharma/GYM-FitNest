<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// ✅ Get gym_id from session
$gym_id = $_SESSION['gym_id'] ?? null;
if (!$gym_id) {
    die("⚠️ Gym ID not found in session. Please log in again.");
}

// ✅ Initialize message
$msg = "";
$msgClass = "";

// --- Fetch gym details ---
$gymQuery = "SELECT * FROM gyms WHERE gym_id = ?";
$stmt = $conn->prepare($gymQuery);
$stmt->bind_param("i", $gym_id);
$stmt->execute();
$result = $stmt->get_result();
$gym = $result->fetch_assoc();
$stmt->close();

// --- Handle form submission ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $description = trim($_POST['description']);
    $opening_time = $_POST['opening_time'];
    $closing_time = $_POST['closing_time'];

    $updated_at = date('Y-m-d H:i:s');

    // ✅ Handle image upload
    $imagePath = $gym['image_url']; // keep old image by default
    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $newName = time() . "_g" . $gym_id . "." . $ext;

        $uploadDir = __DIR__ . "/../../uploads/gyms_images/"; // absolute path for move
        $relativeDir = "uploads/gyms_images/"; // relative path to store in DB

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $targetFile = $uploadDir . $newName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            // Delete old image if exists
            if (!empty($gym['image_url']) && file_exists(__DIR__ . "/../../" . $gym['image_url'])) {
                unlink(__DIR__ . "/../../" . $gym['image_url']);
            }

            // Save relative path
            $imagePath = $relativeDir . $newName;
        } else {
            $msg = "⚠️ Failed to upload image!";
            $msgClass = "alert-danger";
        }
    }

    // --- Update gym details ---
    $updateQuery = "UPDATE gyms SET 
                        name = ?, email = ?, phone = ?, address = ?, description = ?, 
                        opening_time = ?, closing_time = ?, image_url = ?, updated_at = ? 
                    WHERE gym_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param(
        "sssssssssi",
        $name,
        $email,
        $phone,
        $address,
        $description,
        $opening_time,
        $closing_time,
        $imagePath,
        $updated_at,
        $gym_id
    );

    if ($stmt->execute()) {
        header("Location: ../adminPage.php?status=success&msg=Data updated successfully");

        // Refresh gym data
        $gym['name'] = $name;
        $gym['email'] = $email;
        $gym['phone'] = $phone;
        $gym['address'] = $address;
        $gym['description'] = $description;
        $gym['opening_time'] = $opening_time;
        $gym['closing_time'] = $closing_time;
        $gym['image_url'] = $imagePath;
    } else {
        header("Location: ../adminPage.php?status=success&msg=Data updated failed!");
    }

    $stmt->close();
}
?>

<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Gym Settings</h4>
            </div>
            <div class="card-body">

                <!-- Success/Error Message -->
                <?php if ($msg): ?>
                    <div class="alert <?= $msgClass ?> text-center"><?= htmlspecialchars($msg) ?></div>
                <?php endif; ?>

                <!-- Gym Settings Form -->
                <form action="" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="name" class="form-label">Gym Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($gym['name']) ?>" required>
                        <div class="invalid-feedback">Please enter gym name.</div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($gym['email']) ?>" required>
                        <div class="invalid-feedback">Please enter valid email.</div>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($gym['phone']) ?>" required>
                        <div class="invalid-feedback">Please enter phone number.</div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="2" required><?= htmlspecialchars($gym['address']) ?></textarea>
                        <div class="invalid-feedback">Please enter address.</div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($gym['description']) ?></textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label for="opening_time" class="form-label">Opening Time</label>
                            <input type="time" class="form-control" id="opening_time" name="opening_time" value="<?= htmlspecialchars($gym['opening_time']) ?>" required>
                        </div>
                        <div class="col">
                            <label for="closing_time" class="form-label">Closing Time</label>
                            <input type="time" class="form-control" id="closing_time" name="closing_time" value="<?= htmlspecialchars($gym['closing_time']) ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Gym Image</label>
                        <input type="file" class="form-control" id="image" name="image">
                        <?php if (!empty($gym['image_url'])): ?>
                            <img src="<?= htmlspecialchars($gym['image_url']) ?>" alt="Gym Image" class="mt-2" style="width:150px; height:auto; border-radius:5px;">
                        <?php endif; ?>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-5 py-2">Update Settings</button>
                    </div>
                </form>

            </div>
        </div>
    </main>

    <?php require("../assets/link.php"); ?>

    <script>
        // Bootstrap Form Validation
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