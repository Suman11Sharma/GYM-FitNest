<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

$user_id = $_SESSION['customer_id'] ?? null;
if (!$user_id) {
    die("⚠️ User not logged in. Please log in again.");
}

// Pagination settings
$limit = 15;
$page = isset($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Search filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Base query
$searchCondition = "";
$params = [];
$paramTypes = "i"; // user_id type
$params[] = $user_id;

if ($search !== '') {
    $searchCondition = " AND (
        t.name LIKE CONCAT('%', ?, '%') OR 
        b.session_date LIKE CONCAT('%', ?, '%') OR 
        b.payment_status LIKE CONCAT('%', ?, '%') OR 
        b.status LIKE CONCAT('%', ?, '%')
    )";
    $paramTypes .= "ssss";
    $params = array_merge($params, array_fill(0, 4, $search));
}

// Get total rows
$countSql = "SELECT COUNT(*) AS total
             FROM trainer_bookings b
             JOIN trainers t ON b.trainer_id = t.trainer_id
             WHERE b.user_id = ? $searchCondition";
$countStmt = $conn->prepare($countSql);
$countStmt->bind_param($paramTypes, ...$params);
$countStmt->execute();
$totalRows = $countStmt->get_result()->fetch_assoc()['total'];
$countStmt->close();

$totalPages = ceil($totalRows / $limit);

// Fetch paginated data
$sql = "SELECT b.*, t.name AS trainer_name
        FROM trainer_bookings b
        JOIN trainers t ON b.trainer_id = t.trainer_id
        WHERE b.user_id = ? $searchCondition
        ORDER BY b.booking_id DESC
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$paramTypesWithLimit = $paramTypes . "ii";
$paramsWithLimit = array_merge($params, [$limit, $offset]);
$stmt->bind_param($paramTypesWithLimit, ...$paramsWithLimit);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<?php require("../sidelayout.php"); ?>

<!-- Feedback Modal -->
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

<?php if (isset($_GET['status']) && isset($_GET['msg'])): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var feedbackModal = new bootstrap.Modal(document.getElementById("feedbackModal"));
            feedbackModal.show();
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
            <h3 class="mb-3">Trainer Booking History</h3>

            <!-- Search Bar -->
            <form method="GET" class="mb-3 d-flex justify-content-end">
                <input type="text" name="search" class="form-control w-25 me-2"
                    placeholder="Search trainer, date, status..."
                    value="<?= htmlspecialchars($search); ?>">
                <button class="btn btn-our" type="submit">Search</button>
            </form>

            <!-- Booking Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>SN</th>
                            <th>Trainer</th>
                            <th>Session Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Amount (NPR)</th>
                            <th>Payment Status</th>
                            <th>Status</th>
                            <th>Transaction ID</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($bookings) > 0): ?>
                            <?php $sn = $offset + 1; ?>
                            <?php foreach ($bookings as $book): ?>
                                <tr>
                                    <td><?= $sn++; ?></td>
                                    <td><?= htmlspecialchars($book['trainer_name']); ?></td>
                                    <td><?= htmlspecialchars($book['session_date']); ?></td>
                                    <td><?= htmlspecialchars($book['start_time']); ?></td>
                                    <td><?= htmlspecialchars($book['end_time']); ?></td>
                                    <td><?= htmlspecialchars($book['amount']); ?></td>
                                    <td>
                                        <span class="badge bg-<?= $book['payment_status'] === 'paid' ? 'success' : 'warning'; ?>">
                                            <?= ucfirst($book['payment_status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $book['status'] === 'active' ? 'primary' : ($book['status'] === 'completed' ? 'success' : 'secondary'); ?>">
                                            <?= ucfirst($book['status']); ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($book['transaction_id']); ?></td>
                                    <td><?= htmlspecialchars($book['created_at']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted">No booking history found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center mt-3">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?= $i; ?>&search=<?= urlencode($search); ?>"><?= $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </main>
    <?php require("../assets/link.php"); ?>