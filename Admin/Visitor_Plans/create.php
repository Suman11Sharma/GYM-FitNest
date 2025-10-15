<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Create Visitor Plan</h4>
            </div>
            <div class="card-body p-4">
                <form action="store.php" method="POST">

                    <!-- Fee ID  -->
                    <div class="mb-3">
                        <label for="fee_id" class="form-label">Fee ID</label>
                        <input type="text" class="form-control" id="fee_id" name="fee_id" readonly>
                    </div>

                    <!-- Gym ID -->
                    <div class="mb-3">
                        <label for="gym_id" class="form-label">Gym ID</label>
                        <input type="text" class="form-control" id="gym_id" name="gym_id" required>
                    </div>

                    <!-- Visitor Fee -->
                    <div class="mb-3">
                        <label for="visitor_fee" class="form-label">Visitor Fee</label>
                        <input type="number" class="form-control" id="visitor_fee" name="visitor_fee" required>
                    </div>

                    <!-- Created At -->
                    <div class="mb-3">
                        <label for="created_at" class="form-label">Created At</label>
                        <input type="datetime-local" class="form-control" id="created_at" name="created_at" required>
                    </div>

                    <!-- Updated At -->
                    <div class="mb-3">
                        <label for="updated_at" class="form-label">Updated At</label>
                        <input type="datetime-local" class="form-control" id="updated_at" name="updated_at" required>
                    </div>


                    <!-- Submit -->
                    <div class="text-center">
                        <button type="submit" class="btn-our px-5 py-2">Submit</button>
                    </div>

                </form>
            </div>
        </div>
    </main>
    <?php require("../assets/link.php"); ?>

</div>