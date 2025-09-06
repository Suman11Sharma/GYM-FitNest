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

    <?php require("../assets/link.php"); ?>