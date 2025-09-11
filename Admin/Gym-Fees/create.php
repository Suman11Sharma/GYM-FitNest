<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Create Gym Fees</h4>
            </div>
            <div class="card-body">
                <form action="store.php" method="POST" class="needs-validation" novalidate>

                    <!-- Gym ID -->
                    <div class="mb-3">
                        <label for="gym_id" class="form-label">Gym ID</label>
                        <input type="text" class="form-control" id="gym_id" name="gym_id" required>
                        <div class="invalid-feedback">Please enter Gym ID.</div>
                    </div>

                    <!-- Visitor Fee -->
                    <div class="mb-3">
                        <label for="visitor_fee" class="form-label">Visitor Fee (NPR)</label>
                        <input type="number" class="form-control" id="visitor_fee" name="visitor_fee" min="0" required>
                        <div class="invalid-feedback">Please enter a valid visitor fee.</div>
                    </div>

                    <!-- Submit -->
                    <div class="text-center">
                        <button type="submit" class="btn-our px-5 py-2">Save</button>
                    </div>

                </form>
            </div>
        </div>
    </main>
    <?php require("../assets/link.php"); ?>

    <script>
        // Bootstrap form validation
        (() => {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</div>