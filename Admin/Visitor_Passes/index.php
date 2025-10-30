<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../../database/db_connect.php";
session_start();

// ✅ Get Gym ID from session
$gym_id = $_SESSION['gym_id'] ?? null;
if (!$gym_id) {
    die("⚠️ Gym ID not found. Please log in again.");
}

// ✅ Pagination setup
$limit = 15;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// ✅ Search setup
$search = trim($_GET['search'] ?? '');
$searchQuery = "";
$params = [$gym_id];
$types = "i";

if (!empty($search)) {
    $searchQuery = "AND (
        name LIKE ? OR 
        contact LIKE ? OR 
        email LIKE ? OR 
        payment_method LIKE ? OR 
        payment_status LIKE ? OR 
        transaction_id LIKE ?
    )";
    $like = "%" . $search . "%";
    array_push($params, $like, $like, $like, $like, $like, $like);
    $types .= str_repeat("s", 6);
}

// ✅ Count total rows
$count_sql = "SELECT COUNT(*) AS total 
              FROM visitor_passes 
              WHERE gym_id = ? $searchQuery";
$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param($types, ...$params);
$count_stmt->execute();
$totalRows = $count_stmt->get_result()->fetch_assoc()['total'] ?? 0;
$totalPages = ceil($totalRows / $limit);
$count_stmt->close();

// ✅ Fetch records
$sql = "SELECT pass_id, name, contact, email, time_from, time_to, amount, payment_method, payment_status, transaction_id
        FROM visitor_passes 
        WHERE gym_id = ? $searchQuery
        ORDER BY pass_id DESC 
        LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$visitorPasses = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<?php
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

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <!-- Search Form -->
            <form method="GET" class="d-flex align-items-center gap-2">
                <input type="text" name="search" class="form-control"
                    placeholder="Search here"
                    value="<?= htmlspecialchars($search) ?>" style="min-width: 280px;" />
                <button type="submit" class="btn btn-our">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0 align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>SN</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Email</th>
                                <th>Time From</th>
                                <th>Time To</th>
                                <th>Amount (NPR)</th>
                                <th>Payment Method</th>
                                <th>Payment Status</th>
                                <th>Transaction ID</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($visitorPasses)):
                                $sn = $offset + 1;
                                foreach ($visitorPasses as $row): ?>
                                    <tr>
                                        <td><?= $sn++ ?></td>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= htmlspecialchars($row['contact']) ?></td>
                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                        <td><?= htmlspecialchars($row['time_from']) ?></td>
                                        <td><?= htmlspecialchars($row['time_to']) ?></td>
                                        <td><?= number_format($row['amount'], 2) ?></td>
                                        <td><?= htmlspecialchars($row['payment_method']) ?></td>
                                        <td>
                                            <span class="<?= strtolower($row['payment_status']) === 'paid' ? 'text-success fw-bold' : 'text-warning fw-bold' ?>">
                                                <?= ucfirst($row['payment_status']) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($row['transaction_id']) ?></td>
                                        <td>
                                            <a href="edit.php?id=<?= $row['pass_id'] ?>" class="btn btn-sm btn-primary me-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="delete.php?id=<?= $row['pass_id'] ?>"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this record?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td colspan="11" class="text-muted py-3">No records found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav class="mt-3">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">Previous</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>

    </main>
    <?php require("../assets/link.php"); ?>
</div>

<!-- FontAwesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />