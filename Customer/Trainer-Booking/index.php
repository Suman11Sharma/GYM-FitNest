<?php
include "../../database/db_connect.php";
session_start();

// Ensure gym_id is in session
$gym_id = $_SESSION['gym_id'] ?? 0;
if (!$gym_id) die("Gym not selected in session.");

// --- Pagination & Search Setup ---
$limit = 6; // cards per page
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// --- Base Query ---
$sql = "SELECT * FROM trainers WHERE gym_id = ? AND status='active'";
$params = [$gym_id];
$types = "i";

if (!empty($search)) {
    $sql .= " AND (name LIKE ? OR email LIKE ? OR specialization LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= "sss";
}

// --- Count total trainers for pagination ---
$countSql = str_replace("*", "COUNT(*) AS total", $sql);
$stmtCount = $conn->prepare($countSql);
$stmtCount->bind_param($types, ...$params);
$stmtCount->execute();
$totalRows = $stmtCount->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// --- Fetch paginated trainers ---
$sql .= " ORDER BY name ASC LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$trainers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
require("../sidelayout.php");

?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <h3 class="mb-4">Trainers</h3>

        <!-- Search Form -->
        <form method="GET" class="d-flex mb-4 " style="max-width: 400px;">
            <input type="text" name="search" class="form-control me-2"
                placeholder="Search for...."
                value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <!-- Trainer Cards -->
        <div class="row">
            <?php if (!empty($trainers)): ?>
                <?php foreach ($trainers as $trainer): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($trainer['name']); ?></h5>
                                <p class="card-text mb-1"><strong>Email:</strong> <?= htmlspecialchars($trainer['email']); ?></p>
                                <p class="card-text mb-1"><strong>Phone:</strong> <?= htmlspecialchars($trainer['phone']); ?></p>
                                <p class="card-text mb-1"><strong>Specialization:</strong> <?= htmlspecialchars($trainer['specialization']); ?></p>
                                <p class="card-text mb-3"><strong>Rate per Session:</strong> Rs. <?= htmlspecialchars($trainer['rate_per_session']); ?></p>
                                <a href="book.php?trainer_id=<?= $trainer['trainer_id']; ?>" class="btn btn-primary mt-auto">
                                    Book
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted">
                    No trainers found.
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
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
    </main>

    <?php require("../assets/link.php"); ?>