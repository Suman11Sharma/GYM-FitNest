<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// --- Pagination Settings ---
$limit = 15;
$page = isset($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// --- Search ---
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// --- Base SQL ---
$sql = "SELECT cs.*, c.full_name, cp.plan_name 
        FROM customer_subscriptions cs
        LEFT JOIN customers c ON cs.user_id = c.customer_id
        LEFT JOIN customer_plans cp ON cs.plan_id = cp.plan_id
        WHERE 1";

// --- Add Search Conditions ---
if (!empty($search)) {
    $sql .= " AND (
                c.full_name LIKE '%$search%' 
                OR cs.payment_status LIKE '%$search%' 
                OR cs.transaction_id LIKE '%$search%' 
                OR cs.status LIKE '%$search%' 
                OR cp.plan_name LIKE '%$search%'
             )";
}

// --- Count Total for Pagination ---
$totalQuery = mysqli_query($conn, $sql);
$totalRows = mysqli_num_rows($totalQuery);
$totalPages = ceil($totalRows / $limit);

// --- Add Limit ---
$sql .= " ORDER BY cs.created_at DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);
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
    <main>
        <div class="container mt-4">
            <h3 class="mb-3">Customer Subscription</h3>

            <!-- Add & Search -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="create.php" class="btn btn-our">
                    <i class="fas fa-plus me-1"></i> Add New
                </a>

                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2"
                        placeholder="Search by Username, Plan, Payment Status, Transaction ID, or Status"
                        value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-our">Search</button>
                </form>
            </div>

            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover shadow-sm align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>SN</th>
                                <th>Username</th>
                                <th>Plan Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Amount</th>
                                <th>Payment Status</th>
                                <th>Status</th>
                                <th>Transaction ID</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                                $sn = $offset + 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                    <tr>
                                        <td><?= $sn++ ?></td>
                                        <td><?= htmlspecialchars($row['full_name'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($row['plan_name'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($row['start_date']) ?></td>
                                        <td><?= htmlspecialchars($row['end_date']) ?></td>
                                        <td><?= htmlspecialchars($row['amount']) ?></td>
                                        <td>
                                            <?php
                                            if ($row['payment_status'] == 'paid') echo '<span class="badge bg-success">Paid</span>';
                                            elseif ($row['payment_status'] == 'pending') echo '<span class="badge bg-warning text-dark">Pending</span>';
                                            else echo '<span class="badge bg-danger">Failed</span>';
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($row['status'] == 'active') echo '<span class="badge bg-success">Active</span>';
                                            else echo '<span class="badge bg-secondary">Inactive</span>';
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($row['transaction_id']) ?></td>
                                        <td class="text-center">
                                            <a href="edit.php?id=<?= $row['subscription_id'] ?>" class="btn btn-sm btn-warning me-1">Edit</a>

                                        </td>
                                    </tr>
                                <?php
                                }
                            } else { ?>
                                <tr>
                                    <td colspan="10" class="text-center text-muted">No customer subscriptions found.</td>
                                </tr>
                            <?php } ?>
                        </tbody>

                    </table>
                </div>

                <!-- Pagination -->
                <nav>
                    <ul class="pagination justify-content-center mt-3">
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
            </div>
        </div>
    </main>

    <?php require("../assets/link.php"); ?>
</div>