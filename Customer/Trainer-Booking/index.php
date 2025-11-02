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
$sql = "SELECT * FROM trainer_bookings WHERE 1";

if (!empty($search)) {
    $sql .= " AND (booking_id LIKE '%$search%' OR trainer_id LIKE '%$search%' OR user_id LIKE '%$search%')";
}

// --- Count total rows for pagination ---
$countSql = str_replace("*", "COUNT(*) as total", $sql);
$countResult = $conn->query($countSql);
$totalRows = ($countResult && $countResult->num_rows > 0) ? $countResult->fetch_assoc()['total'] : 0;
$totalPages = ceil($totalRows / $limit);

// --- Fetch paginated bookings ---
$sql .= " ORDER BY booking_id DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
$bookings = ($result && $result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <h3 class="mb-3">Trainer Bookings</h3>

        <!-- ✅ Top Controls -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="create.php" class="btn btn-our ms-3">
                <i class="fas fa-plus me-1"></i> Add New Booking
            </a>

            <form method="GET" class="d-flex" style="max-width: 300px;">
                <input type="text" name="search" class="form-control me-2"
                    placeholder="Search by Booking ID, Trainer, or User"
                    value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <!-- ✅ Bookings Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover shadow-sm align-middle">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>SN</th>
                        <th>Booking ID</th>
                        <th>Trainer ID</th>
                        <th>User ID</th>
                        <th>Gym ID</th>
                        <th>Session Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th>Booking Status</th>
                        <th>Transaction ID</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sn = $offset + 1;
                    if (!empty($bookings)):
                        foreach ($bookings as $b): ?>
                            <tr class="text-center">
                                <td><?= $sn++ ?></td>
                                <td><?= htmlspecialchars($b['booking_id']) ?></td>
                                <td><?= htmlspecialchars($b['trainer_id']) ?></td>
                                <td><?= htmlspecialchars($b['user_id']) ?></td>
                                <td><?= htmlspecialchars($b['gym_id']) ?></td>
                                <td><?= htmlspecialchars($b['session_date']) ?></td>
                                <td><?= htmlspecialchars($b['start_time']) ?></td>
                                <td><?= htmlspecialchars($b['end_time']) ?></td>
                                <td><?= htmlspecialchars($b['amount']) ?></td>
                                <td>
                                    <?php
                                    $statusClass = [
                                        'paid' => 'success',
                                        'pending' => 'warning',
                                        'failed' => 'danger'
                                    ][$b['payment_status']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $statusClass ?>">
                                        <?= ucfirst($b['payment_status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $bookingClass = [
                                        'booked' => 'primary',
                                        'completed' => 'success',
                                        'cancelled' => 'danger'
                                    ][$b['status']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $bookingClass ?>">
                                        <?= ucfirst($b['status']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($b['transaction_id']) ?></td>
                                <td class="text-center">
                                    <a href="edit.php?id=<?= $b['booking_id'] ?>" class="btn btn-sm btn-warning me-1">
                                        Edit
                                    </a>
                                    <a href="delete.php?id=<?= $b['booking_id'] ?>" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this booking?');">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach;
                    else: ?>
                        <tr>
                            <td colspan="13" class="text-center text-muted py-3">No trainer bookings found.</td>
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