<?php
include "../database/db_connect.php"; // mysqli connection

// --- Pagination Settings ---
$limit = 10; // gyms per page
$page = isset($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// --- Search ---
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';

// --- Fetch all gyms with their latest active subscription ---
$gymsSql = "SELECT g.gym_id, g.name, g.email, g.phone, g.address, g.image_url, g.description, g.opening_time, g.closing_time,
                   gs.end_date
            FROM gyms g
            LEFT JOIN (
                SELECT gym_id, end_date
                FROM gym_subscriptions
                WHERE status='active'
                ORDER BY end_date DESC
            ) gs ON g.gym_id = gs.gym_id
            WHERE (g.name LIKE '%$search%' 
                   OR g.email LIKE '%$search%'
                   OR g.phone LIKE '%$search%'
                   OR g.address LIKE '%$search%'
                   OR g.description LIKE '%$search%') OR '$search'=''
            GROUP BY g.gym_id
";

$result = mysqli_query($conn, $gymsSql);

$today = date('Y-m-d');
$gyms = [];

// --- Calculate remaining days and prepare array for sorting ---
while ($gym = mysqli_fetch_assoc($result)) {
    $remainingDays = null;
    if (!empty($gym['end_date'])) {
        $remainingDays = max(0, intval((strtotime($gym['end_date']) - strtotime($today)) / (60 * 60 * 24)));
    }
    $gym['remainingDays'] = $remainingDays; // null if no subscription
    $gyms[] = $gym;
}

// --- Sort gyms by remaining days (null at the end) ---
usort($gyms, function ($a, $b) {
    if ($a['remainingDays'] === null) return 1;
    if ($b['remainingDays'] === null) return -1;
    return $a['remainingDays'] <=> $b['remainingDays'];
});

// --- Pagination Slice ---
$totalGyms = count($gyms);
$totalPages = ceil($totalGyms / $limit);
$gyms = array_slice($gyms, $offset, $limit);
?>

<div class="d-flex justify-content-end mb-3">
    <form method="GET" class="d-flex">
        <input type="text" name="search" class="form-control form-control-sm me-2"
            placeholder="Search gyms..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-sm btn-our"><i class="fas fa-search"></i></button>
    </form>
</div>

<!-- Gym Cards -->
<div class="card-container d-flex flex-wrap gap-3">
    <?php foreach ($gyms as $gym):
        $name = htmlspecialchars($gym['name']);
        $description = htmlspecialchars($gym['description'] ?? 'No description available');
        $opening = htmlspecialchars($gym['opening_time'] ?? 'N/A');
        $closing = htmlspecialchars($gym['closing_time'] ?? 'N/A');
        $address = htmlspecialchars($gym['address']);
        $phone = htmlspecialchars($gym['phone']);
        $email = htmlspecialchars($gym['email']);
        $image = htmlspecialchars($gym['image_url']);

        $badge = '<span class="text-secondary">No Subscription</span>';
        if ($gym['remainingDays'] !== null) {
            if ($gym['remainingDays'] <= 5) $colorClass = 'text-danger';
            elseif ($gym['remainingDays'] <= 10) $colorClass = 'text-warning';
            else $colorClass = 'text-success';
            $badge = "<span class='{$colorClass}'>Days Remaining: {$gym['remainingDays']}</span>";
        }
    ?>
        <div class="card custom-card" style="width:18rem;">
            <div class="card-image">
                <img src="../<?= $image ?>" alt="<?= $name ?>" style="width:100%; height:200px; object-fit:cover; border-radius:10px;">
            </div>
            <div class="card-body card-body-custom">
                <h5 class="card-title"><?= $name ?></h5>
                <p><?= $badge ?></p>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary w-100"
                        data-bs-toggle="modal"
                        data-bs-target="#gymDetailModal"
                        data-name="<?= $name ?>"
                        data-description="<?= $description ?>"
                        data-opening="<?= $opening ?>"
                        data-closing="<?= $closing ?>"
                        data-address="<?= $address ?>"
                        data-phone="<?= $phone ?>"
                        data-email="<?= $email ?>">
                        More Detail
                    </button>
                    <a href="Renew-Gym/renew.php?gym_id=<?= $gym['gym_id'] ?>" class="btn btn-our w-100">Renew</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Pagination -->
<nav aria-label="Gym pagination" class="mt-4">
    <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">Previous</a>
            </li>
        <?php endif; ?>

        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <li class="page-item <?= $p == $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $p ?>&search=<?= urlencode($search) ?>"><?= $p ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Next</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

<!-- More Detail Modal -->
<div class="modal fade" id="gymDetailModal" tabindex="-1" aria-labelledby="gymDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="gymDetailModalLabel">Gym Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <h6 id="gymName"></h6>
                <p id="gymDescription"></p>
                <ul>
                    <li><strong>Opening Hours:</strong> <span id="gymTiming"></span></li>
                    <li><strong>Location:</strong> <span id="gymAddress"></span></li>
                    <li><strong>Contact:</strong> <span id="gymPhone"></span></li>
                    <li><strong>Email:</strong> <span id="gymEmail"></span></li>
                </ul>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<!-- JS to populate modal -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameEl = document.getElementById('gymName');
        const descEl = document.getElementById('gymDescription');
        const timingEl = document.getElementById('gymTiming');
        const addressEl = document.getElementById('gymAddress');
        const phoneEl = document.getElementById('gymPhone');
        const emailEl = document.getElementById('gymEmail');

        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', function() {
                nameEl.textContent = this.dataset.name;
                descEl.textContent = this.dataset.description;
                timingEl.textContent = this.dataset.opening + ' – ' + this.dataset.closing;
                addressEl.textContent = this.dataset.address;
                phoneEl.textContent = this.dataset.phone;
                emailEl.textContent = this.dataset.email;
            });
        });
    });
</script>