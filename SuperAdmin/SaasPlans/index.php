<?php require("../sidelayout.php"); ?>
<?php
include "../../database/db_connect.php";

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = "WHERE 1";
if (!empty($search)) {
    $searchEscaped = mysqli_real_escape_string($conn, $search);
    $where .= " AND (
        plan_name LIKE '%$searchEscaped%' OR
        features LIKE '%$searchEscaped%' OR
        status LIKE '%$searchEscaped%'
    )";
}

// Fetch data with search & pagination
$sql = "SELECT plan_id, plan_name, features, amount, duration_months, status, created_at, updated_at
        FROM saas_plans $where 
        ORDER BY created_at DESC 
        LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Count total rows
$countSql = "SELECT COUNT(*) as total FROM saas_plans $where";
$countResult = mysqli_query($conn, $countSql);
$totalRows = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalRows / $limit);
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container mt-4">
            <h3 class="mb-3"><i class="fas fa-list-alt me-2"></i>SaaS Plans Table</h3>

            <!-- Add New + Search -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="create.php" class="btn btn-our">
                    <i class="fas fa-plus me-1"></i> Add New
                </a>

                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Search by name, features, or status..."
                        value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <!-- SaaS Plans Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>SN</th>
                            <th>Plan Name</th>
                            <th>Features</th>
                            <th>Amount (NPR)</th>
                            <th>Duration (Months)</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            $sn = $offset + 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $sn++ . "</td>";
                                echo "<td>" . htmlspecialchars($row['plan_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['features']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['duration_months']) . "</td>";
                                echo "<td><span class='badge bg-" . ($row['status'] === 'active' ? 'success' : 'secondary') . "'>" . htmlspecialchars($row['status']) . "</span></td>";
                                echo "<td>" . date("Y-m-d", strtotime($row['created_at'])) . "</td>";
                                echo "<td class='text-center'>
                                        <a href='edit.php?id=" . $row['plan_id'] . "' class='btn btn-sm btn-warning me-1'>
                                            <i class='fas fa-edit'></i> Edit
                                        </a>
                                        <a href='delete.php?id=" . $row['plan_id'] . "' class='btn btn-sm btn-danger' 
                                           onclick=\"return confirm('Are you sure you want to delete this plan?');\">
                                            <i class='fas fa-trash'></i> Delete
                                        </a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center text-muted'>No plans found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav>
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
        </div>

        <!-- FontAwesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    </main>
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

    <?php require("../assets/link.php"); ?>
</div>