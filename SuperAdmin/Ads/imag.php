<?php
include "../../database/db_connect.php";

// Fetch all ads
$query = "SELECT ad_id, image_url FROM ads";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Ad Images</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container py-4">

    <h2 class="mb-4">Ad Images</h2>
    <div class="row">
        <?php while ($ad = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-4 mb-4">
                <?php if (!empty($ad['image_url'])): ?>
                    <img src="/GYM-FitNest/<?= htmlspecialchars($ad['image_url']) ?>"
                        alt="Ad Image"
                        width="400" height="200"
                        class="img-fluid border shadow-sm"
                        style="object-fit: cover;">
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center bg-light border shadow-sm"
                        style="width:400px; height:200px;">
                        <span class="text-muted">No Image</span>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>


</body>

</html>