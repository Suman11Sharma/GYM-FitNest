<?php
require("../sidelayout.php");
include "../../database/db_connect.php";

// --- Pagination & Search Setup ---
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// --- Base Query ---
$sql = "SELECT * FROM visitor_plans WHERE 1";

if (!empty($search)) {
    $sql .= " AND (fee_id LIKE '%$search%' OR gym_id LIKE '%$search%')";
}

// --- Count total rows for pagination ---
$countSql = str_replace("*", "COUNT(*) as total", $sql);
$countResult = $conn->query($countSql);
$totalRows = ($countResult && $countResult->num_rows > 0) ? $countResult->fetch_assoc()['total'] : 0;
$totalPages = ceil($totalRows / $limit);

// --- Fetch paginated visitor plans ---
$sql .= " ORDER BY fee_id DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
$plans = ($result && $result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <h3 class="mb-3">Visitor Plans</h3>

        <!-- ✅ Top Controls -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="create.php" class="btn btn-our ms-3">
                <i class="fas fa-plus me-1"></i> Add New Plan
            </a>

            <form method="GET" class="d-flex" style="max-width: 300px;">
                <input type="text" name="search" class="form-control me-2"
                    placeholder="Search by Fee ID or Gym ID"
                    value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <!-- ✅ Visitor Plans Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover shadow-sm align-middle">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>SN</th>
                        <th>Fee ID</th>
                        <th>Gym ID</th>
                        <th>Visitor Fee</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sn = $offset + 1;
                    if (!empty($plans)):
                        foreach ($plans as $plan): ?>
                            <tr class="text-center">
                                <td><?= $sn++ ?></td>
                                <td><?= htmlspecialchars($plan['fee_id']) ?></td>
                                <td><?= htmlspecialchars($plan['gym_id']) ?></td>
                                <td><?= htmlspecialchars($plan['visitor_fee']) ?></td>
                                <td><?= htmlspecialchars($plan['created_at']) ?></td>
                                <td><?= htmlspecialchars($plan['updated_at']) ?></td>
                                <td>
                                    <span class="badge <?= $plan['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>">
                                        <?= ucfirst($plan['status']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="edit.php?id=<?= $plan['fee_id'] ?>" class="btn btn-sm btn-warning me-1">Edit</a>
                                    <a href="delete.php?id=<?= $plan['fee_id'] ?>" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this plan?');">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach;
                    else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-3">No visitor plans found.</td>
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

        <!-- FontAwesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    </main>

    <?php require("../assets/link.php"); ?>
</div>