<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// ✅ Ensure session is active
$gym_id = $_SESSION['gym_id'] ?? null;
if (!$gym_id) {
    die("⚠️ Gym ID not found in session. Please log in again.");
}

// ✅ Auto-expire old ads
$conn->query("UPDATE paid_ads 
              SET status='Inactive', updated_at=NOW() 
              WHERE end_date < CURDATE() AND status='Active'");

// ✅ Search setup
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_like = "%" . $search . "%";

// ✅ Pagination setup
$limit = 15;
$page = isset($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// ✅ Base query with search filters
$search_sql = "";
$params = [];
$types = "i"; // for gym_id
$params[] = $gym_id;

if (!empty($search)) {
    $search_sql = " AND (
        ads_plan LIKE ? OR 
        start_date LIKE ? OR 
        end_date LIKE ? OR 
        payment_status LIKE ? OR 
        approval_status LIKE ? OR 
        transaction_id LIKE ? OR 
        status LIKE ?
    )";
    $types .= "sssssss";
    $params = array_merge($params, array_fill(0, 7, $search_like));
}

// ✅ Get total records
$total_sql = "SELECT COUNT(*) FROM paid_ads WHERE gym_id=? $search_sql";
$total_stmt = $conn->prepare($total_sql);
$total_stmt->bind_param($types, ...$params);
$total_stmt->execute();
$total_stmt->bind_result($total_records);
$total_stmt->fetch();
$total_stmt->close();

$total_pages = ceil($total_records / $limit);

// ✅ Fetch paginated data
$data_sql = "
    SELECT ad_id, gym_id, ads_plan, image_url, link_url, start_date, end_date, amount, 
           payment_status, transaction_id, approval_status, status, created_at, updated_at 
    FROM paid_ads 
    WHERE gym_id=? $search_sql
    ORDER BY ad_id DESC 
    LIMIT ? OFFSET ?";

$types_with_limit = $types . "ii";
$params_with_limit = array_merge($params, [$limit, $offset]);

$data_stmt = $conn->prepare($data_sql);
$data_stmt->bind_param($types_with_limit, ...$params_with_limit);
$data_stmt->execute();
$result = $data_stmt->get_result();
$ads = $result->fetch_all(MYSQLI_ASSOC);
$data_stmt->close();

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
            <h3 class="mb-3">My Paid Ads</h3>

            <!-- 🔍 Search + Add New (Side by Side) -->
            <!-- 🔍 Add New (Left) + Search (Right) -->
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


            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>SN</th>
                                <th>Ads Plan</th>
                                <th>Image</th>
                                <th>Link URL</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Amount</th>
                                <th>Payment Status</th>
                                <th>Transaction ID</th>
                                <th>Approval Status</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php if (!empty($ads)): ?>
                                <?php $sn = $offset + 1; ?>
                                <?php foreach ($ads as $ad): ?>
                                    <tr>
                                        <td><?= $sn++ ?></td>
                                        <td><?= htmlspecialchars($ad['ads_plan']) ?></td>

                                        <td>
                                            <?php if (!empty($ad['image_url'])): ?>
                                                <?php $base64 = 'data:image/jpeg;base64,' . base64_encode($ad['image_url']); ?>
                                                <img src="<?= $base64 ?>" alt="Ad Image" style="max-width: 100px;">
                                            <?php else: ?>
                                                <span class="text-muted">No image</span>
                                            <?php endif; ?>
                                        </td>

                                        <td><a href="<?= htmlspecialchars($ad['link_url']) ?>" target="_blank"><?= htmlspecialchars($ad['link_url']) ?></a></td>
                                        <td><?= htmlspecialchars($ad['start_date']) ?></td>
                                        <td><?= htmlspecialchars($ad['end_date']) ?></td>
                                        <td><?= htmlspecialchars($ad['amount']) ?></td>
                                        <td><?= htmlspecialchars($ad['payment_status']) ?></td>
                                        <td><?= htmlspecialchars($ad['transaction_id']) ?></td>
                                        <td><?= htmlspecialchars($ad['approval_status']) ?></td>
                                        <td><?= htmlspecialchars($ad['status']) ?></td>
                                        <td>
                                            <a href="delete.php?id=<?= $ad['ad_id'] ?>"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this ad? This action cannot be undone.');">
                                                Delete
                                            </a>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="12" class="text-center text-muted py-3">No ads found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <nav>
                            <ul class="pagination justify-content-center mt-3">
                                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">Previous</a>
                                </li>

                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                                        <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">Next</a>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </main>

    <?php require("../assets/link.php"); ?>
</div>