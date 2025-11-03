<?php
include "../database/db_connect.php";

// ✅ Get gym_id from session
$gym_id = $_SESSION['gym_id'] ?? null;
if (!$gym_id) {
    die("Gym not found in session");
}

// --- Pagination ---
$limit = 10;
$page = isset($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// --- Search ---
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// --- Main Query ---
$sql = "
    SELECT 
        c.customer_id,
        c.full_name,
        c.email,
        c.phone,
        c.gender,
        c.address,
        c.date_of_birth,
        c.profile_image,
        c.status,
        s.subscription_id,
        s.start_date,
        s.end_date
    FROM customers c
    LEFT JOIN (
        SELECT cs1.*
        FROM customer_subscriptions cs1
        INNER JOIN (
            SELECT user_id, MAX(end_date) AS max_end_date
            FROM customer_subscriptions
            WHERE gym_id = $gym_id
            GROUP BY user_id
        ) cs2 ON cs1.user_id = cs2.user_id AND cs1.end_date = cs2.max_end_date
    ) s ON c.customer_id = s.user_id
    WHERE c.status = 'active'
    AND c.gym_id = $gym_id
";

// --- Search Filter ---
if ($search !== '') {
    $searchEscaped = mysqli_real_escape_string($conn, $search);
    $sql .= " AND (
        c.full_name LIKE '%$searchEscaped%' OR
        c.email LIKE '%$searchEscaped%' OR
        c.phone LIKE '%$searchEscaped%' OR
        c.address LIKE '%$searchEscaped%' OR
        c.status LIKE '%$searchEscaped%'
    )";
}

// --- Sorting (least remaining days first) ---
$sql .= " ORDER BY (CASE WHEN s.end_date IS NULL THEN 999999 ELSE DATEDIFF(s.end_date, CURDATE()) END) ASC";

// --- Pagination ---
$sql .= " LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $sql);

// --- Total Records for Pagination ---
$countQuery = "SELECT COUNT(*) AS total FROM customers WHERE status='active' AND gym_id=$gym_id";
if ($search !== '') {
    $countQuery .= " AND (full_name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' OR address LIKE '%$search%' OR status LIKE '%$search%')";
}
$totalResult = mysqli_query($conn, $countQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalRecords = $totalRow['total'];
$totalPages = ceil($totalRecords / $limit);
?>

<style>
    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1.5rem;
        margin: 20px 0;
    }

    .custom-card {
        border-radius: 15px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.2s;
    }

    .custom-card:hover {
        transform: scale(1.03);
    }

    .card-image img {
        width: 100%;
        height: 240px;
        object-fit: cover;
    }

    .card-body-custom {
        padding: 15px;
        text-align: center;
    }

    .remaining-days {
        font-weight: 600;
        margin-top: 10px;
    }

    .remaining-green {
        color: green;
    }

    .remaining-yellow {
        color: orange;
    }

    .remaining-red {
        color: red;
    }

    .search-bar {
        max-width: 400px;
        margin: 20px auto;
    }
</style>

<div class="container">
    <div class="text-center mt-4">
        <h1 class="fw-bold">Active Customer Subscriptions</h1>
        <hr>
    </div>

    <!-- 🔍 Search -->
    <form method="GET" class="search-bar">
        <div class="input-group">
            <input type="text" class="form-control" name="search" placeholder="Search for...." value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-our" type="submit">Search</button>
        </div>
    </form>

    <!-- 🧾 Cards -->
    <div class="card-container">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <?php
                // --- Profile Image ---
                $imageData = $row['profile_image'];
                $base64Image = !empty($imageData)
                    ? 'data:image/jpeg;base64,' . base64_encode($imageData)
                    : 'https://via.placeholder.com/300x200?text=No+Image';

                // --- Remaining Days ---
                $remainingDays = "No Subscription";
                $remainingClass = "remaining-green";
                if (!empty($row['end_date'])) {
                    $remaining = (strtotime($row['end_date']) - time()) / (60 * 60 * 24);
                    if ($remaining > 10) {
                        $remainingClass = "remaining-green";
                        $remainingDays = floor($remaining) . " Days Left";
                    } elseif ($remaining > 5) {
                        $remainingClass = "remaining-yellow";
                        $remainingDays = floor($remaining) . " Days Left";
                    } elseif ($remaining > 0) {
                        $remainingClass = "remaining-red";
                        $remainingDays = floor($remaining) . " Days Left";
                    } else {
                        $remainingClass = "remaining-red";
                        $remainingDays = "Expired";
                    }
                }

                $subscription_id = $row['subscription_id'] ?? 0;
                ?>
                <div class="card custom-card">
                    <div class="card-image">
                        <img src="<?= $base64Image ?>" alt="<?= htmlspecialchars($row['full_name']) ?>">
                    </div>
                    <div class="card-body card-body-custom">
                        <h5 class="fw-bold"><?= htmlspecialchars($row['full_name']) ?></h5>
                        <p class="remaining-days <?= $remainingClass ?>"><?= $remainingDays ?></p>
                        <div class="d-flex gap-2 mt-3">
                            <button class="btn btn-outline-primary w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#detailModal"
                                data-name="<?= htmlspecialchars($row['full_name']) ?>"
                                data-email="<?= htmlspecialchars($row['email']) ?>"
                                data-phone="<?= htmlspecialchars($row['phone']) ?>"
                                data-gender="<?= htmlspecialchars($row['gender']) ?>"
                                data-address="<?= htmlspecialchars($row['address']) ?>"
                                data-dob="<?= htmlspecialchars($row['date_of_birth']) ?>"
                                data-status="<?= htmlspecialchars($row['status']) ?>"
                                data-image="<?= $base64Image ?>">
                                More Detail
                            </button>
                            <a href="Customer-Subscription/renew.php?user_id=<?= $row['customer_id'] ?>&subscription_id=<?= $subscription_id ?>" class="btn btn-our w-100">Renew</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center text-muted">No active customers found.</p>
        <?php endif; ?>
    </div>

    <!-- 📄 Pagination -->
    <?php if ($totalPages > 1): ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">Previous</a></li>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <?php if ($page < $totalPages): ?>
                    <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Next</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- Customer Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row align-items-center">
                    <div class="col-md-4 text-center">
                        <img id="modalImage" src="" alt="Profile" class="img-fluid rounded-circle shadow" style="width:160px;height:160px;object-fit:cover;">
                    </div>
                    <div class="col-md-8">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Name:</strong> <span id="modalName"></span></li>
                            <li class="list-group-item"><strong>Email:</strong> <span id="modalEmail"></span></li>
                            <li class="list-group-item"><strong>Phone:</strong> <span id="modalPhone"></span></li>
                            <li class="list-group-item"><strong>Gender:</strong> <span id="modalGender"></span></li>
                            <li class="list-group-item"><strong>DOB:</strong> <span id="modalDob"></span></li>
                            <li class="list-group-item"><strong>Address:</strong> <span id="modalAddress"></span></li>
                            <li class="list-group-item"><strong>Status:</strong> <span id="modalStatus"></span></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const detailModal = document.getElementById('detailModal');
    detailModal.addEventListener('show.bs.modal', e => {
        const btn = e.relatedTarget;
        document.getElementById('modalName').innerText = btn.getAttribute('data-name');
        document.getElementById('modalEmail').innerText = btn.getAttribute('data-email');
        document.getElementById('modalPhone').innerText = btn.getAttribute('data-phone');
        document.getElementById('modalGender').innerText = btn.getAttribute('data-gender');
        document.getElementById('modalDob').innerText = btn.getAttribute('data-dob');
        document.getElementById('modalAddress').innerText = btn.getAttribute('data-address');
        document.getElementById('modalStatus').innerText = btn.getAttribute('data-status');
        document.getElementById('modalImage').src = btn.getAttribute('data-image');
    });
</script>