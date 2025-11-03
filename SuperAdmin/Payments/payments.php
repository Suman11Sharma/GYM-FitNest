<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// ✅ Pagination setup
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// ✅ Search setup
$search = trim($_GET['search'] ?? '');
$searchQuery = "";
$params = [];
$types = "";

if (!empty($search)) {
    $searchQuery = "WHERE (
        g.name LIKE ? OR 
        g.email LIKE ? OR 
        g.phone LIKE ? OR 
        gp.payment_type LIKE ? OR 
        gp.payout_status LIKE ?
    )";
    $like = "%" . $search . "%";
    array_push($params, $like, $like, $like, $like, $like);
    $types .= str_repeat("s", 5);
}

// ✅ Count total rows
$count_sql = "SELECT COUNT(*) AS total 
              FROM gym_payouts gp
              JOIN gyms g ON gp.gym_id = g.gym_id
              $searchQuery";
$count_stmt = $conn->prepare($count_sql);
if (!empty($searchQuery)) {
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$totalRows = $count_stmt->get_result()->fetch_assoc()['total'] ?? 0;
$totalPages = ceil($totalRows / $limit);
$count_stmt->close();

// ✅ Fetch records
$sql = "SELECT 
            gp.payout_id, gp.payment_type, gp.amount, gp.payout_status, gp.created_at, gp.paid_at,
            g.gym_id, g.name AS gym_name, g.email AS gym_email, g.phone AS gym_phone
        FROM gym_payouts gp
        JOIN gyms g ON gp.gym_id = g.gym_id
        $searchQuery
        ORDER BY gp.created_at DESC
        LIMIT ? OFFSET ?";

$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$payouts = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<?php require("../sidelayout.php"); ?>

<div id="layoutSidenav_content">
    <main class="container-fluid mt-4">

        <!-- ✅ Title and Search Row -->
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <h4 class="fw-bold mb-2 mb-sm-0">All Gym Payouts</h4>

            <form method="GET" class="d-flex align-items-center gap-2">
                <input type="text" name="search" class="form-control"
                    placeholder="Search gym, email, payment type or status"
                    value="<?= htmlspecialchars($search) ?>" style="min-width: 280px;" />
                <button type="submit" class="btn btn-dark">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <!-- ✅ Table -->
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0 align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>SN</th>
                                <th>Gym Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Payment Type</th>
                                <th>Amount (Rs)</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Paid At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($payouts)):
                                $sn = $offset + 1;
                                foreach ($payouts as $row): ?>
                                    <tr>
                                        <td><?= $sn++ ?></td>
                                        <td><?= htmlspecialchars($row['gym_name']) ?></td>
                                        <td><?= htmlspecialchars($row['gym_email']) ?></td>
                                        <td><?= htmlspecialchars($row['gym_phone']) ?></td>
                                        <td><?= ucfirst(str_replace('_', ' ', $row['payment_type'])) ?></td>
                                        <td><?= number_format($row['amount'], 2) ?></td>
                                        <td>
                                            <?php if ($row['payout_status'] == 'paid'): ?>
                                                <span class="badge bg-success">Paid</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date("Y-m-d h:i A", strtotime($row['created_at'])) ?></td>
                                        <td><?= $row['paid_at'] ? date("Y-m-d h:i A", strtotime($row['paid_at'])) : '-' ?></td>
                                        <td>
                                            <a href="edit.php?id=<?= $row['payout_id'] ?>" class="btn btn-sm btn-primary me-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td colspan="10" class="text-muted py-3">No payout records found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ✅ Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav class="mt-3">
                <ul class="pagination justify-content-center flex-wrap">
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

        <div style="height: 40px;"></div> <!-- ✅ Prevents sticky bottom overlap -->

    </main>

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <?php require("../assets/link.php"); ?>