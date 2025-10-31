<?php
include("../../database/db_connect.php");

// --- Pagination Settings ---
$limit = 15;
$page = isset($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// --- Search ---
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// --- Base SQL Query ---
$sql = "SELECT s.subscription_id, s.gym_id, g.name, s.plan_name, s.start_date, s.end_date,
               s.amount, s.payment_status, s.transaction_id, s.status
        FROM gym_subscriptions s
        LEFT JOIN gyms g ON s.gym_id = g.gym_id
        WHERE 1";

if (!empty($search)) {
    $sql .= " AND (
        s.gym_id LIKE ? OR 
        g.name LIKE ? OR 
        s.plan_name LIKE ? OR 
        s.start_date LIKE ? OR 
        s.end_date LIKE ? OR 
        s.amount LIKE ? OR 
        s.payment_status LIKE ? OR 
        s.transaction_id LIKE ? OR 
        s.status LIKE ?
    )";
}

$sql .= " ORDER BY s.subscription_id DESC LIMIT ? OFFSET ?";

// --- Prepare Statement ---
$stmt = $conn->prepare($sql);

if (!empty($search)) {
    $likeSearch = "%{$search}%";
    $stmt->bind_param(
        "sssssssssii",
        $likeSearch,
        $likeSearch,
        $likeSearch,
        $likeSearch,
        $likeSearch,
        $likeSearch,
        $likeSearch,
        $likeSearch,
        $likeSearch,
        $limit,
        $offset
    );
} else {
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

// --- Total Count for Pagination ---
$countSql = "SELECT COUNT(*) AS total 
             FROM gym_subscriptions s
             LEFT JOIN gyms g ON s.gym_id = g.gym_id
             WHERE 1";
if (!empty($search)) {
    $searchSafe = $conn->real_escape_string($search);
    $countSql .= " AND (
        s.gym_id LIKE '%$searchSafe%' OR 
        g.name LIKE '%$searchSafe%' OR 
        s.plan_name LIKE '%$searchSafe%' OR 
        s.start_date LIKE '%$searchSafe%' OR 
        s.end_date LIKE '%$searchSafe%' OR 
        s.amount LIKE '%$searchSafe%' OR 
        s.payment_status LIKE '%$searchSafe%' OR 
        s.transaction_id LIKE '%$searchSafe%' OR 
        s.status LIKE '$searchSafe%'
    )";
}
$totalResult = $conn->query($countSql);
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

require("../sidelayout.php");
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container mt-4">
            <h3 class="mb-3">Gym Subscriptions</h3>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="create.php" class="btn btn-our ms-3">
                    <i class="fas fa-plus me-1"></i> Renew Gym
                </a>

                <!-- 🔍 Search Form -->
                <form method="GET" class="d-flex mb-3" style="max-width: 300px;">
                    <input type="text" name="search" class="form-control me-2"
                        placeholder="Search by ID, name, plan, etc."
                        value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- 📋 Subscription Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>SN</th>
                            <th>Gym ID</th>
                            <th>Gym Name</th>
                            <th>Plan Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Amount (NPR)</th>
                            <th>Payment Status</th>
                            <th>Transaction ID</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sn = $offset + 1;
                        if ($result->num_rows > 0):
                            while ($row = $result->fetch_assoc()):
                        ?>
                                <tr>
                                    <td><?= $sn++ ?></td>
                                    <td><?= htmlspecialchars($row['gym_id']) ?></td>
                                    <td><?= htmlspecialchars($row['name'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['plan_name']) ?></td>
                                    <td><?= htmlspecialchars($row['start_date']) ?></td>
                                    <td><?= htmlspecialchars($row['end_date']) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['amount'], 2)) ?></td>
                                    <td>
                                        <span class="badge bg-<?= strtolower($row['payment_status']) === 'paid' ? 'success' : 'warning' ?>">
                                            <?= htmlspecialchars(ucfirst($row['payment_status'])) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($row['transaction_id']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= strtolower($row['status']) === 'active' ? 'success' : 'secondary' ?>">
                                            <?= htmlspecialchars(ucfirst($row['status'])) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="edit.php?id=<?= $row['subscription_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="delete.php?id=<?= $row['subscription_id'] ?>"
                                            onclick="return confirm('Are you sure you want to delete this subscription?');"
                                            class="btn btn-sm btn-danger">Delete</a>
                                    </td>
                                </tr>
                        <?php
                            endwhile;
                        else:
                            echo '<tr><td colspan="11" class="text-center text-muted">No subscriptions found.</td></tr>';
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- 📄 Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center mt-3">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </main>

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <?php require("../assets/link.php"); ?>
</div>