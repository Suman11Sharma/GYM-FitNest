<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Start transaction
        $conn->begin_transaction();

        // Insert About Us Card
        $title = $_POST['card1Title'] ?? '';
        $subtitle = $_POST['card1Subtitle'] ?? '';
        $descriptions = $_POST['card1Description'] ?? [];

        $sql = "INSERT INTO about_us (main_title, quotes) 
                VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $title, $subtitle);
        $stmt->execute();
        $aboutId = $stmt->insert_id;

        // Insert Description Points
        $sqlPoint = "INSERT INTO about_us_points (about_id, description_point) VALUES (?, ?)";
        $stmtPoint = $conn->prepare($sqlPoint);

        foreach ($descriptions as $desc) {
            if (!empty(trim($desc))) {
                $stmtPoint->bind_param("is", $aboutId, $desc);
                $stmtPoint->execute();
            }
        }

        // Commit transaction
        $conn->commit();

        header("Location: index.php?status=success&msg=About Us card created successfully");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: index.php?status=error&msg=Failed to save data: " . $e->getMessage());
        exit();
    }
}
?>

<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-md py-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0 text-center flex-grow-1">Add About Us Card</h2>
                <a href="./index.php" class="btn btn-light btn-sm border ms-3" title="Back to List">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>

            <form action="create.php" method="POST" class="needs-validation" novalidate>

                <!-- ABOUT US CARD -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">About Us Card</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Heading Title</label>
                            <input type="text" class="form-control" name="card1Title" placeholder="Enter heading title" required>
                            <div class="invalid-feedback">Please enter a heading title.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description Body (List Format)</label>
                            <div id="descriptionList1">
                                <div class="input-group mb-2">
                                    <span class="input-group-text">•</span>
                                    <input type="text" name="card1Description[]" class="form-control" placeholder="Description point" required>
                                    <button type="button" class="btn btn-danger" onclick="removeDescription(this)">Remove</button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="addDescription('descriptionList1', 'card1Description[]')">Add More</button>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sub-title (in quotation)</label>
                            <input type="text" class="form-control" name="card1Subtitle" placeholder='"Your quote here"' required>
                            <div class="invalid-feedback">Please enter a subtitle.</div>
                        </div>
                    </div>
                </div>

                <!-- SUBMIT -->
                <div class="text-center">
                    <button type="submit" class="btn btn-our px-5 py-2">Submit</button>
                </div>
            </form>
        </div>

        <script>
            function addDescription(containerId, inputName) {
                const container = document.getElementById(containerId);
                const div = document.createElement("div");
                div.className = "input-group mb-2";
                div.innerHTML = `
                    <span class="input-group-text">•</span>
                    <input type="text" name="${inputName}" class="form-control" placeholder="Description point" required>
                    <button type="button" class="btn btn-danger" onclick="removeDescription(this)">Remove</button>
                `;
                container.appendChild(div);
            }

            function removeDescription(button) {
                button.parentElement.remove();
            }
        </script>
    </main>
    <?php require("../layouts/footer.php"); ?>

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