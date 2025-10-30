<?php
include "database/db_connect.php"; // mysqli connection

if (isset($_GET['lat']) && isset($_GET['lon'])) {
    $userLat = floatval($_GET['lat']);
    $userLon = floatval($_GET['lon']);

    // Haversine formula to calculate distance (in kilometers)
    $sql = "
        SELECT gym_id, name, email, phone, address, image_url, description, opening_time, closing_time, latitude, longitude,
        (6371 * ACOS(
            COS(RADIANS($userLat)) *
            COS(RADIANS(latitude)) *
            COS(RADIANS(longitude) - RADIANS($userLon)) +
            SIN(RADIANS($userLat)) *
            SIN(RADIANS(latitude))
        )) AS distance
        FROM gyms
        ORDER BY distance ASC
    ";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die('Query Error: ' . mysqli_error($conn));
    }

    while ($gym = mysqli_fetch_assoc($result)) {
        $imagePath = htmlspecialchars($gym['image_url']);

        // Escape data for safety
        $name = htmlspecialchars($gym['name']);
        $description = htmlspecialchars($gym['description'] ?? 'No description available');
        $opening = htmlspecialchars($gym['opening_time'] ?? 'N/A');
        $closing = htmlspecialchars($gym['closing_time'] ?? 'N/A');
        $address = htmlspecialchars($gym['address']);
        $phone = htmlspecialchars($gym['phone']);
        $email = htmlspecialchars($gym['email']);
        $distance = round($gym['distance'], 2);
        echo '
<div class="card custom-card">
    <div class="card-image">
        <img src="' . $imagePath . '" alt="' . $name . '" style="width:100%; height:260px; object-fit:cover; border-radius:10px;">
    </div>
    <div class="card-body card-body-custom">
        <h5 class="card-title">' . $name . '</h5>
        <p>Distance: ' . $distance . ' km</p>
        <div class="d-flex gap-2">

            <a href="#"
               class="btn btn-outline-primary btn-cool w-100 view-details-btn"
               data-bs-toggle="modal"
               data-bs-target="#gymDetailModal"
               data-name="' . $name . '"
               data-description="' . $description . '"
               data-opening="' . $opening . '"
               data-closing="' . $closing . '"
               data-address="' . $address . '"
               data-phone="' . $phone . '"
               data-email="' . $email . '">
               More Detail
             <a href="store_pass.php?gym_id=' . $gym['gym_id'] . '"
         class="btn btn-primary btn-cool w-100 btn-bgcolor">
               Get Pass
            </a>
        </div>
    </div>
</div>';
    }
}
