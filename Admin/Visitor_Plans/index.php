<?php
require("../sidelayout.php");
include "../../database/db_connect.php";

// --- Pagination settings ---
$rowsPerPage = 15;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $rowsPerPage;

// --- Search term ---
$search = $_GET['search'] ?? '';
$searchParam = "%$search%";

// --- Query with search ---
$stmt = $conn->prepare("SELECT * FROM visitor_plans WHERE fee_id LIKE ? OR gym_id LIKE ? LIMIT ?, ?");
$stmt->bind_param("ssii", $searchParam, $searchParam, $offset, $rowsPerPage);
$stmt->execute();
$result = $stmt->get_result();

// --- Count total rows for pagination ---
$countStmt = $conn->prepare("SELECT COUNT(*) AS total FROM visitor_plans WHERE fee_id LIKE ? OR gym_id LIKE ?");
$countStmt->bind_param("ss", $searchParam, $searchParam);
$countStmt->execute();
$totalRows = $countStmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $rowsPerPage);
?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <h3 class="mb-3">Visitor Plans</h3>
            <div class="card-body">

                <!-- Header with Add New -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="create.php" class="btn btn-our">
                        <i class="fas fa-plus me-1"></i> Add New
                    </a>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>Fee ID</th>
                                <th>Gym ID</th>
                                <th>Visitor Fee</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['fee_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['gym_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['visitor_fee']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No records found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>

            </div>
        </div>
    </main>
    <?php require("../assets/link.php"); ?>
</div>