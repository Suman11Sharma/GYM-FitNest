<?php
include "../../database/db_connect.php";

if (!isset($_GET['id'])) {
    die("No gym ID provided");
}

$gym_id = intval($_GET['id']);
$sql = "SELECT * FROM gyms WHERE gym_id = $gym_id";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Gym not found");
}

$gym = mysqli_fetch_assoc($result);
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
            <h2 class="mb-0 text-center flex-grow-1">Edit Gym</h2>
            <a href="./index.php" class="btn btn-light btn-sm border ms-3" title="Back">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>

        <form action="update.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="gym_id" value="<?php echo $gym['gym_id']; ?>">

            <div class="mb-3">
                <label class="form-label">Company Name</label>
                <input type="text" class="form-control" name="companyName"
                    value="<?php echo htmlspecialchars($gym['name']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" name="address"
                    value="<?php echo htmlspecialchars($gym['address']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Contact Number</label>
                <input type="tel" class="form-control" name="contactNumber"
                    value="<?php echo htmlspecialchars($gym['phone']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email"
                    value="<?php echo htmlspecialchars($gym['email']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Current Gym Photo</label><br>
                <img src="../../<?php echo htmlspecialchars($gym['image_url']); ?>"
                    alt="Gym Photo" style="max-width:150px;border:1px solid #ccc;">
            </div>

            <div class="mb-3">
                <label class="form-label">Change Gym Photo</label>
                <input type="file" class="form-control" name="gymPhoto" accept="image/*">
                <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($gym['image_url']); ?>">
            </div>

            <!-- Map Section -->
            <div class="mb-3">
                <label class="form-label">Select Location</label>
                <div id="map"></div>
                <div class="row mt-2 g-2">
                    <div class="col-md-6">
                        <label class="form-label">Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude"
                            value="<?php echo $gym['latitude']; ?>" required readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude"
                            value="<?php echo $gym['longitude']; ?>" required readonly>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-warning px-5 py-2">Update</button>
            </div>
        </form>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Load existing values or default Pokhara
        const startLat = parseFloat(document.getElementById('latitude').value) || 28.2096;
        const startLng = parseFloat(document.getElementById('longitude').value) || 83.9856;

        const map = L.map('map').setView([startLat, startLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let marker = L.marker([startLat, startLng], {
            draggable: true
        }).addTo(map);

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