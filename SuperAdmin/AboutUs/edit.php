<?php
require("../sidelayout.php");
include "../../database/db_connect.php";

// Get the about_id from GET
$aboutId = $_GET['id'] ?? null;

if (!$aboutId) {
    header("Location: index.php?status=error&msg=Invalid request");
    exit();
}

// Fetch about_us data
$stmt = $conn->prepare("SELECT main_title, quotes, status FROM about_us WHERE about_id = ?");
$stmt->bind_param("i", $aboutId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php?status=error&msg=About Us card not found");
    exit();
}

$about = $result->fetch_assoc();

// Fetch points
$stmtPoints = $conn->prepare("SELECT description_point FROM about_us_points WHERE about_id = ?");
$stmtPoints->bind_param("i", $aboutId);
$stmtPoints->execute();
$resPoints = $stmtPoints->get_result();

$points = [];
while ($row = $resPoints->fetch_assoc()) {
    $points[] = $row['description_point'];
}

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-md py-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0 text-center flex-grow-1">Edit AboutUs Card</h2>
                <a href="index.php" class="btn btn-light btn-sm border ms-3" title="Back to Index">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>

            <form action="update.php" method="POST" class="needs-validation" novalidate>
                <!-- Hidden about_id -->
                <input type="hidden" name="about_id" value="<?= $aboutId ?>">

                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">About Us Card</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Heading Title</label>
                            <input type="text" class="form-control" name="card1Title" placeholder="Enter heading title" value="<?= htmlspecialchars($about['main_title']) ?>" required>
                            <div class="invalid-feedback">Please enter a heading title.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description Body (List Format)</label>
                            <div id="descriptionList1">
                                <?php foreach ($points as $point): ?>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">•</span>
                                        <input type="text" name="card1Description[]" class="form-control" placeholder="Description point" value="<?= htmlspecialchars($point) ?>" required>
                                        <button type="button" class="btn btn-danger" onclick="removeDescription(this)">Remove</button>
                                    </div>
                                <?php endforeach; ?>
                                <?php if (empty($points)): ?>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">•</span>
                                        <input type="text" name="card1Description[]" class="form-control" placeholder="Description point" required>
                                        <button type="button" class="btn btn-danger" onclick="removeDescription(this)">Remove</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="addDescription('descriptionList1', 'card1Description[]')">Add More</button>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sub-title (in quotation)</label>
                            <input type="text" class="form-control" name="card1Subtitle" placeholder='"Your quote here"' value="<?= htmlspecialchars($about['quotes']) ?>" required>
                            <div class="invalid-feedback">Please enter a subtitle.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="status" required>
                                <option value="active" <?= $about['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= $about['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                            <div class="invalid-feedback">Please select a status.</div>
                        </div>
                    </div>
                </div>

                <!-- SUBMIT -->
                <div class="text-center">
                    <button type="submit" class="btn btn-our px-5 py-2">Update</button>
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
                    <input type="text" name="${inputName}" class="form-control" placeholder="Description point">
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