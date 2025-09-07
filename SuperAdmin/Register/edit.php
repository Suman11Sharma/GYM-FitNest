<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <title>Edit Gym</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>

        <body class="bg-light">
            <div class="container-md py-5">
                <h2 class="mb-4 text-center">Gym</h2>

                <form action="#" method="POST" enctype="multipart/form-data">

                    <!-- Gym Name -->
                    <div class="mb-3">
                        <label for="gymName" class="form-label">Gym Name</label>
                        <input type="text" class="form-control" id="gymName" name="gymName" placeholder="Enter gym name" required>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="gymEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="gymEmail" name="gymEmail" placeholder="Enter email" required>
                    </div>

                    <!-- Phone -->
                    <div class="mb-3">
                        <label for="gymPhone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="gymPhone" name="gymPhone" placeholder="Enter phone number" required>
                    </div>

                    <!-- Address -->
                    <div class="mb-3">
                        <label for="gymAddress" class="form-label">Address</label>
                        <input type="text" class="form-control" id="gymAddress" name="gymAddress" placeholder="Enter address" required>
                    </div>

                    <!-- Latitude & Longitude -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Enter latitude" required>
                        </div>
                        <div class="col-md-6">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Enter longitude" required>
                        </div>
                    </div>

                    <!-- Photo Upload -->
                    <div class="mb-4">
                        <label for="gymPhoto" class="form-label">Upload Gym Photo</label>
                        <input class="form-control" type="file" id="gymPhoto" name="gymPhoto" accept="image/*">
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-our px-5 py-2">Update</button>
                        <a href="index.php" class="btn btn-secondary px-4 py-2 ms-2">Cancel</a>
                    </div>

                </form>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        </body>

        </html>
    </main>
    <?php require("../assets/link.php"); ?>
</div>