<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main>

        <body class="bg-light">

            <div class="container-md py-5">
                <h2 class="mb-4 text-center">Advertisement</h2>

                <form action="#" method="POST" enctype="multipart/form-data">

                 
                    <div class="mb-3">
                        <label for="companyName" class="form-label">Company Name</label>
                        <input type="text" class="form-control" id="companyName" name="companyName" placeholder="Enter company name" required>
                    </div>

        
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

                    <div class="mb-4">
                        <label for="adImage" class="form-label">Upload Advertisement Image</label>
                        <input class="form-control" type="file" id="adImage" name="adImage" accept="image/*" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-our px-5 py-2">Submit</button>
                    </div>

                </form>
            </div>

        </body>


    </main>
    <?php require("../assets/link.php"); ?>