<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

// ✅ Get trainer_id from session
$trainer_id = $_SESSION['trainer_id'] ?? null;
if (!$trainer_id) {
    die("⚠️ Trainer ID not found in session. Please log in again.");
}

// --- Pagination settings ---
$limit = 15;
$page = isset($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// --- Search ---
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// --- Base Query ---
$query = "
    SELECT b.*, 
           c.full_name AS customer_name
    FROM trainer_bookings b
    JOIN customers c ON b.user_id = c.customer_id
    WHERE b.trainer_id = ?
";

// --- Apply search filters ---
if ($search !== '') {
    $query .= " AND (
        c.full_name LIKE ? OR 
        b.session_date LIKE ? OR 
        b.payment_status LIKE ? OR 
        b.status LIKE ?
    )";
}

$query .= " ORDER BY b.created_at DESC LIMIT ? OFFSET ?";

$stmt = $conn->prepare($query);

if ($search !== '') {
    $like = "%{$search}%";
    $stmt->bind_param("issssii", $trainer_id, $like, $like, $like, $like, $limit, $offset);
} else {
    $stmt->bind_param("iii", $trainer_id, $limit, $offset);
}

$stmt->execute();
$bookings = $stmt->get_result();

// --- Get total records for pagination ---
$count_query = "
    SELECT COUNT(*) AS total 
    FROM trainer_bookings b 
    JOIN customers c ON b.user_id = c.customer_id
    WHERE b.trainer_id = ?
";

if ($search !== '') {
    $count_query .= " AND (
        c.full_name LIKE ? OR 
        b.session_date LIKE ? OR 
        b.payment_status LIKE ? OR 
        b.status LIKE ?
    )";
}

$count_stmt = $conn->prepare($count_query);

if ($search !== '') {
    $count_stmt->bind_param("issss", $trainer_id, $like, $like, $like, $like);
} else {
    $count_stmt->bind_param("i", $trainer_id);
}

$count_stmt->execute();
$total_result = $count_stmt->get_result()->fetch_assoc();
$total_records = $total_result['total'] ?? 0;
$total_pages = ceil($total_records / $limit);

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
            <h3 class="mb-3">My Booking Requests</h3>

            <form method="GET" class="mb-3 d-flex justify-content-end">
                <input type="text" name="search"
                    class="form-control w-25 me-2"
                    placeholder="Search trainer, date, status..."
                    value="<?= htmlspecialchars($search); ?>">
                <button class="btn btn-our" type="submit">Search</button>
            </form>


            <!-- ✅ Booking Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>SN</th>
                            <th>Customer Name</th>
                            <th>Session Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Amount (NPR)</th>
                            <th>Payment Status</th>
                            <th>Status</th>
                            <th>Transaction ID</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($bookings->num_rows > 0): ?>
                            <?php
                            $sn = $offset + 1;
                            while ($row = $bookings->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?= $sn++; ?></td>
                                    <td><?= htmlspecialchars($row['customer_name']); ?></td>
                                    <td><?= htmlspecialchars($row['session_date']); ?></td>
                                    <td><?= htmlspecialchars($row['start_time']); ?></td>
                                    <td><?= htmlspecialchars($row['end_time']); ?></td>
                                    <td><?= htmlspecialchars($row['amount']); ?></td>
                                    <td>
                                        <span class="badge bg-<?= $row['payment_status'] === 'paid' ? 'success' : 'warning'; ?>">
                                            <?= htmlspecialchars(ucfirst($row['payment_status'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $row['status'] === 'active' ? 'primary' : ($row['status'] === 'completed' ? 'success' : 'secondary'); ?>">
                                            <?= htmlspecialchars(ucfirst($row['status'])); ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($row['transaction_id']); ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <div class="btn-group">
                                                <a href="edit.php?id=<?= $row['booking_id']; ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>

                                        </div>
                                    </td>

                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted">No bookings found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- ✅ Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mt-3">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?= $i; ?>&search=<?= urlencode($search); ?>"><?= $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </main>
    <?php require("../assets/link.php"); ?>