<?php
include "../../database/db_connect.php";

$message = "";
$status  = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $companyName  = trim($_POST['companyName']);
    $address      = trim($_POST['address']);
    $contact      = trim($_POST['contactNumber']);
    $email        = trim($_POST['email']);
    $latitude     = trim($_POST['latitude']);
    $longitude    = trim($_POST['longitude']);

    // 1. Validation: required fields
    if (empty($companyName) || empty($address) || empty($contact) || empty($email) || empty($latitude) || empty($longitude)) {
        $message = "❌ All fields are required.";
        $status  = "error";
    } else {
        // 2. Validation: unique email
        $checkEmail = mysqli_query($conn, "SELECT gym_id FROM gyms WHERE email = '$email'");
        if (mysqli_num_rows($checkEmail) > 0) {
            $message = "❌ This email is already registered.";
            $status  = "error";
        } else {
            // 3. Handle file upload
            $targetDir = "../../uploads/gyms_images/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileName = time() . "_" . basename($_FILES["gymPhoto"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            $dbFilePath = "uploads/gyms_images/" . $fileName;

            if (move_uploaded_file($_FILES["gymPhoto"]["tmp_name"], $targetFilePath)) {
                // 4. Insert into DB
                $sql = "INSERT INTO gyms (name, email, phone, address, image_url, latitude, longitude, created_at, updated_at)
                        VALUES ('$companyName', '$email', '$contact', '$address', '$dbFilePath', '$latitude', '$longitude', NOW(), NOW())";

                if (mysqli_query($conn, $sql)) {
                    $message = "✅ Gym added successfully!";
                    $status  = "success";
                } else {
                    $message = "❌ Database error: " . mysqli_error($conn);
                    $status  = "error";
                }
            } else {
                $message = "❌ Error uploading gym photo.";
                $status  = "error";
            }
        }
    }

    // Redirect back with message
    header("Location: create.php?status=$status&msg=" . urlencode($message));
    exit;
}
?>

<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 300px;
            border-radius: 10px;
        }
    </style>

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0 text-center flex-grow-1">Registration Form</h2>
            <!-- Back button -->
            <a href="./index.php" class="btn btn-light btn-sm border ms-3" title="Back to Home">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>

        <!-- Feedback Modal -->
        <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-<?php echo ($_GET['status'] ?? '') === 'success' ? 'success' : 'danger'; ?> text-white">
                        <h5 class="modal-title" id="feedbackModalLabel">
                            <?php echo ($_GET['status'] ?? '') === 'success' ? 'Success' : 'Error'; ?>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php echo isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : ''; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-<?php echo ($_GET['status'] ?? '') === 'success' ? 'success' : 'danger'; ?>" data-bs-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>


        <form action="create.php" method="POST" enctype="multipart/form-data">
            <!-- Company Name -->
            <div class="mb-3">
                <label class="form-label">Company Name</label>
                <input type="text" class="form-control" name="companyName" required>
            </div>

            <!-- Address Section -->
            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" name="address" required>
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
            <!-- Gym Photo Upload -->
            <div class="mb-3">
                <label class="form-label">Gym Photo</label>
                <input type="file" class="form-control" name="gymPhoto" accept="image/*" required>
                <small class="text-muted">Upload a photo of your gym (JPG, PNG, max 5MB)</small>
            </div>

            <!-- Map Section -->
            <div class="mb-3">
                <label class="form-label">Select Location</label>
                <div id="map"></div>
                <div class="row mt-2 g-2">
                    <div class="col-md-6">
                        <label class="form-label">Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" required readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude" required readonly>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-our px-5 py-2">Submit</button>
            </div>
        </form>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        // Default Pokhara, Nepal
        const defaultLat = 28.2096;
        const defaultLng = 83.9856;

        // Initialize Map
        const map = L.map('map').setView([defaultLat, defaultLng], 13);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add marker
        let marker = L.marker([defaultLat, defaultLng], {
            draggable: true
        }).addTo(map);

        // Set initial values
        document.getElementById("latitude").value = defaultLat;
        document.getElementById("longitude").value = defaultLng;

        // Update on drag
        marker.on('dragend', function() {
            const lat = marker.getLatLng().lat;
            const lng = marker.getLatLng().lng;
            document.getElementById("latitude").value = lat.toFixed(6);
            document.getElementById("longitude").value = lng.toFixed(6);
        });

        // Update on map click
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById("latitude").value = e.latlng.lat.toFixed(6);
            document.getElementById("longitude").value = e.latlng.lng.toFixed(6);
        });
    </script>


    <!-- js for the model -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (isset($_GET['status']) && isset($_GET['msg'])): ?>
                var feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
                feedbackModal.show();
            <?php endif; ?>
        });
    </script>


    <?php require("../assets/link.php"); ?>