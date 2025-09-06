<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <title>Advertisement</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>

        <body class="bg-light">
            <div class="container-md py-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0 text-center flex-grow-1">Advertisement</h2>
                    <a href="../index.php" class="btn btn-light btn-sm border ms-3" title="Back to Home">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>


                <form action="#" method="POST" enctype="multipart/form-data">

                    <!-- Company Name -->
                    <div class="mb-3">
                        <label for="companyName" class="form-label">Company Name</label>
                        <input type="text" class="form-control" id="companyName" name="companyName" placeholder="Enter company name" required>
                    </div>

                    <!-- Duration (number + select weeks/months) -->
                    <div class="mb-3">
                        <label class="form-label">Duration</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="durationValue" placeholder="Enter duration" required min="1">
                            <select class="form-select" name="durationUnit" required>
                                <option value="weeks">Weeks</option>
                                <option value="months">Months</option>
                            </select>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="mb-4">
                        <label for="adImage" class="form-label">Upload Advertisement Image</label>
                        <input class="form-control" type="file" id="adImage" name="adImage" accept="image/*" required>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class=" btn-our px-5 py-2">Submit</button>
                    </div>

                </form>
            </div>



            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        </body>

        </html>

    </main>
    <?php require("../assets/link.php"); ?>