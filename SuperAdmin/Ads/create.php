<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main>
        <!-- Add/Edit Form -->
        <div class="card mb-4">
            <div class="card-header">Add / Edit Advertisement</div>
            <div class="card-body">
                <form id="adsForm">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="companyName" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="companyName" required>
                        </div>
                        <div class="col-md-3">
                            <label for="duration" class="form-label">Duration</label>
                            <input type="text" class="form-control" id="duration" placeholder="e.g. 10 days" required>
                        </div>

                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Save Ad</button>
                        <button type="reset" class="btn btn-secondary">Clear</button>
                    </div>
                </form>
            </div>
        </div>

    </main>
    <?php require("../assets/link.php"); ?>