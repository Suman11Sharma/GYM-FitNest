<?php
include "../../database/db_connect.php";

// ✅ Pagination setup
$limit = 15;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// ✅ Search setup
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

// ✅ Base query
$baseQuery = "FROM customers WHERE 1";

// ✅ Add search filter
if (!empty($search)) {
    $safeSearch = $conn->real_escape_string($search);
    $baseQuery .= " AND (
        full_name LIKE '$safeSearch%' 
        OR email LIKE '$safeSearch%' 
        OR phone LIKE '$safeSearch%' 
        OR gym_id LIKE '$safeSearch%'
    )";
}

// ✅ Get total for pagination
$countSql = "SELECT COUNT(*) AS total " . $baseQuery;
$countResult = $conn->query($countSql);
$totalRows = ($countResult && $countResult->num_rows > 0) ? (int)$countResult->fetch_assoc()['total'] : 0;
$totalPages = ceil($totalRows / $limit);

// ✅ Fetch data with limit
$sql = "SELECT customer_id, gym_id, full_name, gender, email, phone, address, password, 
               date_of_birth, profile_image, join_date, status 
        $baseQuery 
        ORDER BY join_date DESC 
        LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);
$customers = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }
}
?>

<?php require("../sidelayout.php"); ?>

<!-- ✅ Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-<?php echo ($_GET['status'] ?? '') === 'success' ? 'success' : 'danger'; ?> text-white">
                <h5 class="modal-title" id="feedbackModalLabel">
                    <?php echo ($_GET['status'] ?? '') === 'success' ? 'Success' : 'Error'; ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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

<!-- ✅ Auto-show modal -->
<?php if (isset($_GET['status']) && isset($_GET['msg'])): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var modal = new bootstrap.Modal(document.getElementById("feedbackModal"));
            modal.show();
            document.getElementById("feedbackModal").addEventListener("hidden.bs.modal", function() {
                const url = new URL(window.location.href);
                url.search = "";
                window.history.replaceState({}, document.title, url);
            });
        });
    </script>
<?php endif; ?>

<div id="layoutSidenav_content">
    <main>
        <div class="container mt-4">
            <h3 class="mb-3">Customers Table</h3>

            <!-- ✅ Top Controls -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Add New -->
                <a href="create.php" class="btn btn-our ms-3">
                    <i class="fas fa-plus me-1"></i> Add New
                </a>

                <!-- Search Form -->
                <form method="GET" class="d-flex" style="max-width: 300px;">
                    <input type="text" name="search" class="form-control me-2" placeholder="Search by name, email, phone, gym id" value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- ✅ Customers Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>SN</th>
                            <th>Gym ID</th>
                            <th>Full Name</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Password</th>
                            <th>Date of Birth</th>
                            <th>Profile Image</th>
                            <th>Join Date</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sn = $offset + 1;
                        if (!empty($customers)):
                            foreach ($customers as $cust): ?>
                                <tr>
                                    <td><?= $sn++ ?></td>
                                    <td><?= htmlspecialchars($cust['gym_id']) ?></td>
                                    <td><?= htmlspecialchars($cust['full_name']) ?></td>
                                    <td><?= ucfirst(htmlspecialchars($cust['gender'])) ?></td>
                                    <td><?= htmlspecialchars($cust['email']) ?></td>
                                    <td><?= htmlspecialchars($cust['phone']) ?></td>
                                    <td><?= htmlspecialchars($cust['address']) ?></td>
                                    <td><?= htmlspecialchars($cust['password']) ?></td>
                                    <td><?= htmlspecialchars($cust['date_of_birth']) ?></td>
                                    <td>
                                        <?php if (!empty($cust['profile_image'])): ?>
                                            <img src="../../uploads/<?= htmlspecialchars($cust['profile_image']) ?>" alt="Profile" width="50" height="50" class="rounded-circle">
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
                                    <td class="text-center">
                                        <a href="edit.php?id=<?= $cust['customer_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="delete.php?id=<?= $cust['customer_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this customer?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else: ?>
                            <tr>
                                <td colspan="13" class="text-center text-muted">No customers found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- ✅ Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center mt-3">
                        <!-- Previous -->
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">Previous</a>
                        </li>

                        <!-- Pages -->
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next -->
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