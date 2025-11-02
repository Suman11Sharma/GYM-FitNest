<?php
include "../../database/admin_authentication.php";
include "../../database/db_connect.php";

// --- Ensure trainer is logged in ---
if (!isset($_SESSION['trainer_id'])) {
    header("Location: ../login.php");
    exit();
}

$trainer_id = intval($_SESSION['trainer_id']);

// --- Pagination & Search Setup ---
$limit = 15;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// --- Base Query (only this trainer's availability) ---
$sql = "SELECT * FROM trainer_availability WHERE trainer_id = ?";
$params = [$trainer_id];
$types = "i";

if (!empty($search)) {
    $sql .= " AND (day_of_week LIKE ? OR start_time LIKE ? OR end_time LIKE ?)";
    $searchParam = "%$search%";
    $types .= "sss";
    array_push($params, $searchParam, $searchParam, $searchParam);
}

// --- Count total rows for pagination ---
$countSql = str_replace("*", "COUNT(*) as total", $sql);
$stmtCount = $conn->prepare($countSql);
$stmtCount->bind_param($types, ...$params);
$stmtCount->execute();
$countResult = $stmtCount->get_result();
$totalRows = ($countResult && $countResult->num_rows > 0) ? $countResult->fetch_assoc()['total'] : 0;
$totalPages = ceil($totalRows / $limit);

// --- Fetch paginated availability ---
$sql .= " ORDER BY availability_id DESC LIMIT ?, ?";
$types .= "ii";
array_push($params, $offset, $limit);

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$availability = ($result && $result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];
require("../sidelayout.php");
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

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <h3 class="mb-3">My Availability</h3>

        <!-- Search -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="create.php" class="btn btn-our ms-3">
                <i class="fas fa-plus me-1"></i> Add Availability
            </a>
            <form method="GET" class="d-flex" style="max-width: 300px;">
                <input type="text" name="search" class="form-control me-2"
                    placeholder="Search by Day or Time"
                    value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <!-- Availability Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover shadow-sm align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>SN</th>
                        <th>Day of Week</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sn = $offset + 1;
                    if (!empty($availability)):
                        foreach ($availability as $a): ?>
                            <tr class="text-center">
                                <td><?= $sn++ ?></td>
                                <td><?= ucfirst(htmlspecialchars($a['day_of_week'])); ?></td>
                                <td><?= htmlspecialchars($a['start_time']); ?></td>
                                <td><?= htmlspecialchars($a['end_time']); ?></td>
                                <td>
                                    <a href="edit.php?id=<?= $a['availability_id']; ?>" class="btn btn-sm btn-warning me-1">Edit</a>
                                    <a href="delete.php?id=<?= $a['availability_id']; ?>" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this availability?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach;
                    else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">No availability found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav class="mt-3">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Next</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </main>

    <?php require("../assets/link.php"); ?>
