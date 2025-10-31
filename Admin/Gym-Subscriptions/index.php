<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

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
$searchParams = [];
$types = "i"; // gym_id

if (!empty($search)) {
    $searchQuery = "AND (
        plan_name LIKE ? OR 
        start_date LIKE ? OR 
        end_date LIKE ? OR 
        amount LIKE ? OR 
        payment_status LIKE ? OR 
        transaction_id LIKE ? OR 
        status LIKE ?
    )";
    $like = "%" . $search . "%";
    $searchParams = [$like, $like, $like, $like, $like, $like, $like];
    $types .= str_repeat("s", count($searchParams));
}

// ✅ Count total records
$sql_count = "SELECT COUNT(*) AS total FROM gym_subscriptions WHERE gym_id = ? $searchQuery";
$count_stmt = $conn->prepare($sql_count);
$count_stmt->bind_param($types, $gym_id, ...$searchParams);
$count_stmt->execute();
$total_records = $count_stmt->get_result()->fetch_assoc()['total'] ?? 0;
$count_stmt->close();

$total_pages = ceil($total_records / $limit);

// ✅ Fetch records
$sql = "SELECT subscription_id, plan_name, start_date, end_date, amount, payment_status, transaction_id, status
        FROM gym_subscriptions
        WHERE gym_id = ? $searchQuery
        ORDER BY subscription_id DESC
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);

$types .= "ii";
$params = array_merge([$gym_id], $searchParams, [$limit, $offset]);
$stmt->bind_param($types, ...$params);

$stmt->execute();
$result = $stmt->get_result();
$subscriptions = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<?php require("../sidelayout.php"); ?>
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
    <main>
        <div class="container mt-4">
            <h3 class="mb-3">Gym Subscriptions Table</h3>

            <!-- 🔍 Search + Add New (Your format) -->
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">

                <!-- Left: Add New Button -->
                <a href="create.php" class="btn btn-our mb-2 mb-md-0">
                    <i class="fas fa-plus"></i> Add New
                </a>

                <!-- Right: Search Form -->
                <form method="GET" class="d-flex align-items-center gap-2">
                    <input type="text" name="search" class="form-control"
                        placeholder="Search here"
                        value="<?= htmlspecialchars($search) ?>" style="min-width: 280px;" />
                    <button type="submit" class="btn btn-our">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- 📋 Subscriptions Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>SN</th>
                            <th>Plan Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Amount (NPR)</th>
                            <th>Payment Status</th>
                            <th>Transaction ID</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sn = $offset + 1;
                        if (!empty($subscriptions)):
                            foreach ($subscriptions as $sub): ?>
                                <tr>
                                    <td><?= $sn++ ?></td>
                                    <td><?= htmlspecialchars($sub['plan_name']) ?></td>
                                    <td><?= htmlspecialchars($sub['start_date']) ?></td>
                                    <td><?= htmlspecialchars($sub['end_date']) ?></td>
                                    <td><?= number_format($sub['amount'], 2) ?></td>
                                    <td>
                                        <span class="<?= strtolower($sub['payment_status']) === 'paid' ? 'text-success fw-bold' : 'text-warning fw-bold' ?>">
                                            <?= ucfirst($sub['payment_status']) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($sub['transaction_id']) ?></td>
                                    <td>
                                        <span class="<?= strtolower($sub['status']) === 'active' ? 'text-success fw-bold' : 'text-secondary fw-bold' ?>">
                                            <?= ucfirst($sub['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">No subscriptions found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- 📄 Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mt-3">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">Previous</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Next</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>

        </div>

        <!-- FontAwesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    </main>
    <?php require("../assets/link.php"); ?>
</div>