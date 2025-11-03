<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// ✅ Get logged-in gym ID from session
$gym_id = $_SESSION['gym_id'] ?? null;
if (!$gym_id) {
    die("⚠️ Gym ID not found in session. Please log in again.");
}

// ✅ Search and Pagination
$search = trim($_GET['search'] ?? '');
$limit = 15;
$page = isset($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// ✅ Build base SQL
$sql = "SELECT subscription_id, plan_name, start_date, end_date, amount, payment_status, transaction_id, status, created_at 
        FROM gym_subscriptions 
        WHERE gym_id = ?";

$params = [$gym_id];
$types = "i";

// ✅ Add search filter
if (!empty($search)) {
    $sql .= " AND (plan_name LIKE ? OR payment_status LIKE ? OR transaction_id LIKE ? OR status LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "$search%";
    $types .= "ssss";
}

// ✅ Add order and pagination
$sql .= " ORDER BY created_at DESC LIMIT $limit OFFSET $offset";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// ✅ Count total records for pagination
$count_sql = "SELECT COUNT(*) AS total FROM gym_subscriptions WHERE gym_id = ?";
$count_params = [$gym_id];
$count_types = "i";
if (!empty($search)) {
    $count_sql .= " AND (plan_name LIKE ? OR payment_status LIKE ? OR transaction_id LIKE ? OR status LIKE ?)";
    $count_params = array_merge($count_params, ["%$search%", "%$search%", "%$search%", "%$search%"]);
    $count_types .= "ssss";
}
$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param($count_types, ...$count_params);
$count_stmt->execute();
$total_rows = $count_stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);
?>

<?php require("../sidelayout.php"); ?>

<div id="layoutSidenav_content">
    <main class="container-fluid mt-4">
        <!-- ✅ Title and Search Row -->
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <h4 class="fw-bold mb-2 mb-sm-0">Subscription History</h4>

            <form method="GET" class="d-flex align-items-center gap-2">
                <input type="text" name="search" class="form-control"
                    placeholder="Search plan, status, or transaction..."
                    value="<?= htmlspecialchars($search) ?>" style="min-width: 280px;" />
                <button type="submit" class="btn btn-our">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <!-- ✅ Data Table -->
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>SN</th>
                            <th>Plan Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Amount (Rs)</th>
                            <th>Payment Status</th>
                            <th>Transaction ID</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            $sn = $offset + 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$sn}</td>
                                    <td>{$row['plan_name']}</td>
                                    <td>{$row['start_date']}</td>
                                    <td>{$row['end_date']}</td>
                                    <td>{$row['amount']}</td>
                                    <td><span class='badge bg-" . ($row['payment_status'] == 'paid' ? 'success' : 'warning') . "'>" . ucfirst($row['payment_status']) . "</span></td>
                                    <td>{$row['transaction_id']}</td>
                                    <td><span class='badge bg-" . ($row['status'] == 'active' ? 'success' : 'secondary') . "'>" . ucfirst($row['status']) . "</span></td>
                                    <td>{$row['created_at']}</td>
                                </tr>";
                                $sn++;
                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center text-muted py-3'>No subscription records found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ✅ Pagination -->
        <?php if ($total_pages > 1): ?>
            <nav class="mt-3">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </main>
</div>