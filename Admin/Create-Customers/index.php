<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// ✅ Get gym_id from session
if (!isset($_SESSION['gym_id'])) {
    die("❌ Session expired. Please log in again.");
}
$gym_id = $_SESSION['gym_id'];

// ✅ Pagination setup
$rowsPerPage = 15;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $rowsPerPage;

// ✅ Search setup
$search = trim($_GET['search'] ?? '');
$searchParam = "%{$search}%";

// ✅ Main Query
$stmt = $conn->prepare("
    SELECT customer_id, gym_id, full_name, gender, email, phone, address, date_of_birth, profile_image, join_date, status
    FROM customers
    WHERE gym_id = ?
      AND (
        full_name LIKE ? 
        OR email LIKE ? 
        OR phone LIKE ?
        OR address LIKE ?
      )
    ORDER BY customer_id DESC
    LIMIT ?, ?
");
$stmt->bind_param("issssii", $gym_id, $searchParam, $searchParam, $searchParam, $searchParam, $offset, $rowsPerPage);
$stmt->execute();
$result = $stmt->get_result();
$customers = $result->fetch_all(MYSQLI_ASSOC);

// ✅ Total rows count for pagination
$countStmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM customers
    WHERE gym_id = ?
      AND (
        full_name LIKE ? 
        OR email LIKE ? 
        OR phone LIKE ?
        OR address LIKE ?
      )
");
$countStmt->bind_param("issss", $gym_id, $searchParam, $searchParam, $searchParam, $searchParam);
$countStmt->execute();
$totalRows = $countStmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $rowsPerPage);
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
            <h3 class="mb-3">Customers</h3>

            <!-- ✅ Top Controls -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="create.php" class="btn btn-our ms-3">
                    <i class="fas fa-plus me-1"></i> Add New
                </a>

                <form method="GET" class="d-flex" style="max-width: 300px;">
                    <input type="text" name="search" class="form-control me-2"
                        placeholder="Search by name, email, phone, address"
                        value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- ✅ Customers Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm align-middle">
                    <thead class="table-dark">
                        <tr class="text-center">
                            <th>SN</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>DOB</th>
                            <th>Profile Image</th>
                            <th>Join Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sn = $offset + 1;
                        if (!empty($customers)):
                            foreach ($customers as $cust): ?>
                                <tr class="text-center">
                                    <td><?= $sn++ ?></td>
                                    <td><?= htmlspecialchars($cust['full_name']) ?></td>
                                    <td><?= ucfirst(htmlspecialchars($cust['gender'])) ?></td>
                                    <td><?= htmlspecialchars($cust['email']) ?></td>
                                    <td><?= htmlspecialchars($cust['phone']) ?></td>
                                    <td><?= htmlspecialchars($cust['address']) ?></td>
                                    <td><?= htmlspecialchars($cust['date_of_birth']) ?></td>
                                    <td>
                                        <?php if (!empty($cust['profile_image'])): ?>
                                            <img src="data:image/jpeg;base64,<?= base64_encode($cust['profile_image']) ?>"
                                                alt="Profile" width="50" height="50"
                                                class="rounded-circle border">
                                        <?php else: ?>
                                            <span class="text-muted">No Image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($cust['join_date']) ?></td>
                                    <td>
                                        <span class="badge <?= $cust['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>">
                                            <?= ucfirst($cust['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="edit.php?id=<?= $cust['customer_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="delete.php?id=<?= $cust['customer_id'] ?>"
                                            onclick="return confirm('Are you sure you want to delete this customer?');"
                                            class="btn btn-sm btn-danger">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else: ?>
                            <tr>
                                <td colspan="11" class="text-center text-muted py-3">No customers found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- ✅ Pagination -->
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
        </div>

        <!-- FontAwesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    </main>

    <?php require("../assets/link.php"); ?>
</div>