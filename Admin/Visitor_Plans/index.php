<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// ✅ Get gym_id from session
$gym_id = $_SESSION['gym_id'] ?? null;
if (!$gym_id) {
    die("⚠️ Gym ID not found in session. Please log in again.");
}

// ✅ Fetch visitor plans
$query = "SELECT fee_id, visitor_fee, status, created_at, updated_at 
          FROM visitor_plans 
          WHERE gym_id = ? 
          ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $gym_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-<?php echo ($_GET['status'] ?? '') === 'success' ? 'success' : 'danger'; ?> text-white">
                <h5 class="modal-title" id="feedbackModalLabel">
                    <?php echo ($_GET['status'] ?? '') === 'success' ? 'Success' : 'Error'; ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php echo isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : ''; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-<?php echo ($_GET['status'] ?? '') === 'success' ? 'success' : 'danger'; ?>" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Auto-trigger modal if feedback exists -->
<?php if (isset($_GET['status']) && isset($_GET['msg'])): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var feedbackModal = new bootstrap.Modal(document.getElementById("feedbackModal"));
            feedbackModal.show();

            // When modal is closed, remove query params so it won't reopen on refresh
            document.getElementById("feedbackModal").addEventListener("hidden.bs.modal", function() {
                const url = new URL(window.location.href);
                url.search = ""; // clear query string
                window.history.replaceState({}, document.title, url);
            });
        });
    </script>
<?php endif; ?>

<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main class="container mt-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0">Visitor Plans</h3>
            <a href="create.php" class="btn btn-our px-4 py-2">
                <i class="fas fa-plus me-2"></i> Add Visitor Plan
            </a>
        </div>

        <!-- Table Card -->
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center shadow-sm">
                        <thead class="table-dark">
                            <tr>
                                <th>SN</th>
                                <th>Visitor Fee (Rs.)</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0):
                                $sn = 1;
                                while ($row = $result->fetch_assoc()):
                            ?>
                                    <tr>
                                        <td><?= $sn++; ?></td>
                                        <td><?= htmlspecialchars($row['visitor_fee']); ?></td>
                                        <td><?= htmlspecialchars(date("Y-m-d H:i", strtotime($row['created_at']))); ?></td>
                                        <td><?= htmlspecialchars(date("Y-m-d H:i", strtotime($row['updated_at']))); ?></td>
                                        <td>
                                            <span class="badge bg-<?= $row['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                                <?= ucfirst($row['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="edit.php?id=<?= $row['fee_id']; ?>" class="btn btn-sm btn-info text-white">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="delete.php?id=<?= $row['fee_id']; ?>"
                                                    onclick="return confirm('Are you sure you want to delete this plan?');"
                                                    class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                endwhile;
                            else:
                                ?>
                                <tr>
                                    <td colspan="6" class="text-muted py-3">No visitor plans found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>
    <?php require("../assets/link.php"); ?>
</div>