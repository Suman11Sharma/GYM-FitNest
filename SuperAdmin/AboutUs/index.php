<?php
require("../sidelayout.php");

include "../../database/db_connect.php";

// --- Pagination settings ---
$rowsPerPage = 15;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $rowsPerPage;

// --- Search term ---
$search = $_GET['search'] ?? '';
$searchParam = "$search%";

// --- Count total rows ---
$totalSql = "SELECT COUNT(*) as total FROM about_us WHERE main_title LIKE ? OR quotes LIKE ? OR status LIKE ?";
$totalStmt = $conn->prepare($totalSql);
$totalStmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
$totalStmt->execute();
$totalResult = $totalStmt->get_result()->fetch_assoc();
$totalRows = $totalResult['total'];
$totalPages = ceil($totalRows / $rowsPerPage);

// --- Fetch 15 rows of about_us ---
$sql = "SELECT * FROM about_us 
        WHERE main_title LIKE ? OR quotes LIKE ? OR status LIKE ?
        ORDER BY created_at DESC
        LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssii", $searchParam, $searchParam, $searchParam, $offset, $rowsPerPage);
$stmt->execute();
$result = $stmt->get_result();

$aboutData = [];
$aboutIds = [];
while ($row = $result->fetch_assoc()) {
    $aboutData[$row['about_id']] = $row;
    $aboutData[$row['about_id']]['descriptions'] = [];
    $aboutIds[] = $row['about_id'];
}

// --- Fetch all points for these about_ids ---
if (!empty($aboutIds)) {
    $placeholders = implode(',', array_fill(0, count($aboutIds), '?'));
    $types = str_repeat('i', count($aboutIds));
    $sqlPoints = "SELECT about_id, description_point FROM about_us_points WHERE about_id IN ($placeholders)";
    $stmtPoints = $conn->prepare($sqlPoints);
    $stmtPoints->bind_param($types, ...$aboutIds);
    $stmtPoints->execute();
    $resPoints = $stmtPoints->get_result();
    while ($p = $resPoints->fetch_assoc()) {
        $aboutData[$p['about_id']]['descriptions'][] = $p['description_point'];
    }
}
?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <?php include '../Layouts/header.php'; ?>
        <h2 class="mb-4">About Us Cards</h2>

        <div class="d-flex justify-content-between mb-3">
            <a href="create.php" class="btn btn-our">+ Add Card Info</a>
            <form class="d-flex" method="GET">
                <input type="text" class="form-control me-2" name="search" placeholder="Search heading, quote, status..." value="<?= htmlspecialchars($search) ?>">
                <button class="btn btn-primary" type="submit">Search</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>SN</th>
                        <th>Main Title</th>
                        <th>Quotes</th>
                        <th>Status</th>
                        <th>Description Points</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($aboutData)):
                        $sn = $offset + 1;
                        foreach ($aboutData as $card):
                    ?>
                            <tr>
                                <td><?= $sn++ ?></td>
                                <td><?= htmlspecialchars($card['main_title']) ?></td>
                                <td><em><?= htmlspecialchars($card['quotes']) ?></em></td>
                                <td><?= ucfirst($card['status']) ?></td>
                                <td>
                                    <ul class="mb-0 ps-3">
                                        <?php foreach ($card['descriptions'] as $point): ?>
                                            <li><?= htmlspecialchars($point) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                                <td>
                                    <a href="edit.php?id=<?= $card['about_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="delete.php?id=<?= $card['about_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No data available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </main>
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



    <?php require("../assets/link.php"); ?>