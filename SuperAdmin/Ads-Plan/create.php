<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name          = trim($_POST['name']);
    $duration_days = (int) $_POST['duration_days'];
    $price         = (float) $_POST['price'];
    $description   = trim($_POST['description']);
    $status        = trim($_POST['status']);

    if (empty($name) || empty($duration_days) || empty($price) || empty($description) || empty($status)) {
        $msg = "All fields are required.";
        $statusCode = "error";
    } else {
        $sql = "INSERT INTO ad_plans (name, duration_days, price, description, status) 
                VALUES ('$name', '$duration_days', '$price', '$description', '$status')";

        if (mysqli_query($conn, $sql)) {
            $msg = "Plan added successfully!";
            $statusCode = "success";
        } else {
            $msg = "Database error: " . mysqli_error($conn);
            $statusCode = "error";
        }
    }

    // Redirect to the same page with feedback
    header("Location: index.php?status=$statusCode&msg=" . urlencode($msg));
    exit;
}
?>


<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Create Ad Plan</h4>
            </div>
            <div class="card-body">


                <form action="create.php" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Plan Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback">Please enter the plan name.</div>
                    </div>

                    <!-- Duration (Days) -->
                    <div class="mb-3">
                        <label for="duration_days" class="form-label">Duration (Days)</label>
                        <input type="number" class="form-control" id="duration_days" name="duration_days" min="1" required>
                        <div class="invalid-feedback">Please enter a valid duration in days.</div>
                    </div>

                    <!-- Price -->
                    <div class="mb-3">
                        <label for="price" class="form-label">Price (NPR)</label>
                        <input type="number" class="form-control" id="price" name="price" min="0" step="0.01" required>
                        <div class="invalid-feedback">Please enter a valid price.</div>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        <div class="invalid-feedback">Please enter a description.</div>
                    </div>

                    <!-- Status (Dropdown) -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="" disabled selected>-- Select Status --</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <div class="invalid-feedback">Please select a status.</div>
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
    </script>