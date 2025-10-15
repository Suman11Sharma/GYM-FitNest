<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require("../sidelayout.php");
include "../../database/db_connect.php";

// --- Pagination settings ---
$rowsPerPage = 15;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $rowsPerPage;

// --- Search term ---
$search = $_GET['search'] ?? '';
$searchParam = "%{$search}%";

// --- Query with pagination and search ---
$stmt = $conn->prepare("
    SELECT * FROM visitor_passes 
    WHERE pass_id LIKE ? 
       OR gym_id LIKE ? 
       OR name LIKE ? 
    ORDER BY pass_id DESC 
    LIMIT ?, ?
");
$stmt->bind_param("sssii", $searchParam, $searchParam, $searchParam, $offset, $rowsPerPage);
$stmt->execute();
$result = $stmt->get_result();

// --- Total record count for pagination ---
$countStmt = $conn->prepare("
    SELECT COUNT(*) AS total FROM visitor_passes 
    WHERE pass_id LIKE ? 
       OR gym_id LIKE ? 
       OR name LIKE ?
");
$countStmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
$countStmt->execute();
$totalRows = $countStmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $rowsPerPage);
?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold text-dark mb-0">Visitor Passes</h3>

        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="create.php" class="btn btn-our">
                <i class="fas fa-plus me-1"></i> Add New
            </a>
        </div>

        <!-- Table -->
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0 text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Pass ID</th>
                                <th>Gym ID</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Email</th>
                                <th>Time From</th>
                                <th>Time To</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Payment Status</th>
                                <th>Transaction ID</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['pass_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['gym_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['contact']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['time_from']); ?></td>
                                        <td><?php echo htmlspecialchars($row['time_to']); ?></td>
                                        <td><?php echo htmlspecialchars($row['amount']); ?></td>
                                        <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                                        <td><?php echo htmlspecialchars($row['payment_status']); ?></td>
                                        <td><?php echo htmlspecialchars($row['transaction_id']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="12" class="text-muted py-3">No records found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav class="mt-3">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </main>
    <?php require("../assets/link.php"); ?>
</div>