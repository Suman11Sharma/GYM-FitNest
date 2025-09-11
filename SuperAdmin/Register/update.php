<?php
include "../../database/db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gym_id = intval($_POST['gym_id']);
    $companyName = trim($_POST['companyName']);
    $address = trim($_POST['address']);
    $contact = trim($_POST['contactNumber']);
    $email = trim($_POST['email']);
    $latitude = trim($_POST['latitude']);
    $longitude = trim($_POST['longitude']);

    $image_url = $_POST['existing_image']; // fallback

    if (!empty($_FILES['gymPhoto']['name'])) {
        $targetDir = "../../uploads/gyms_images/";
        $fileName = time() . "_" . basename($_FILES["gymPhoto"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $dbFilePath = "uploads/gyms_images/" . $fileName;

        if (move_uploaded_file($_FILES["gymPhoto"]["tmp_name"], $targetFilePath)) {
            $image_url = $dbFilePath;
        }
    }

    $sql = "UPDATE gyms SET 
            name='$companyName',
            address='$address',
            phone='$contact',
            email='$email',
            image_url='$image_url',
            latitude='$latitude',
            longitude='$longitude',
            updated_at=NOW()
            WHERE gym_id=$gym_id";

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?status=success&msg=Gym updated successfully");
        exit;
    } else {
        echo "Update failed: " . mysqli_error($conn);
    }
}
