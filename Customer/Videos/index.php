<?php
include "../../database/db_connect.php";

// --- Pagination ---
$limit = 15;
$page = isset($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// --- Search ---
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = "WHERE status = 'active'";

if (!empty($search)) {
    $searchEscaped = mysqli_real_escape_string($conn, $search);
    $where .= " AND (title LIKE '%$searchEscaped%' OR description LIKE '%$searchEscaped%')";
}

// --- Total Count for Pagination ---
$countQuery = "SELECT COUNT(*) AS total FROM videos $where";
$countResult = mysqli_query($conn, $countQuery);
$totalRows = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalRows / $limit);

// --- Main Query ---
$query = "SELECT * FROM videos $where ORDER BY uploaded_at DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

require("../sidelayout.php");
?>

<div id="layoutSidenav_content">
    <main class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">🎬 Video Gallery</h3>
            <form class="d-flex" method="GET" action="">
                <input type="text" name="search" class="form-control me-2" placeholder="Search videos..."
                    value="<?= htmlspecialchars($search); ?>">
                <button class="btn btn-dark" type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="row g-4">
                <?php while ($row = mysqli_fetch_assoc($result)):
                    $videoPath = "../../uploads/videos/" . htmlspecialchars($row['filename']);
                ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card video-card shadow-lg border-0 rounded-4 overflow-hidden h-100"
                            data-bs-toggle="modal"
                            data-bs-target="#videoModal"
                            data-video="<?= $videoPath ?>"
                            style="cursor: pointer;">

                            <div class="ratio ratio-16x9 bg-dark">
                                <video class="w-100" preload="metadata" muted>
                                    <source src="<?= $videoPath ?>#t=1" type="video/mp4">
                                </video>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title mb-0 text-dark"><?= htmlspecialchars($row['title']); ?></h5>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <nav aria-label="Video Pagination" class="mt-5">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search); ?>">Previous</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search); ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search); ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php else: ?>
            <div class="alert alert-info text-center mt-4">
                No videos found.
            </div>
        <?php endif; ?>
    </main>

    <!-- Video Modal -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content bg-dark">
                <div class="modal-body p-0 position-relative">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-2" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="ratio ratio-16x9">
                        <video id="modalVideo" class="w-100 rounded-bottom" controls>
                            <source src="" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require("../assets/link.php"); ?>

    <script>
        const videoModal = document.getElementById('videoModal');
        const modalVideo = document.getElementById('modalVideo');

        videoModal.addEventListener('show.bs.modal', event => {
            const card = event.relatedTarget;
            const videoSrc = card.getAttribute('data-video');
            modalVideo.querySelector('source').src = videoSrc;
            modalVideo.load();
            modalVideo.play();
        });

        videoModal.addEventListener('hidden.bs.modal', () => {
            modalVideo.pause();
            modalVideo.currentTime = 0;
        });
    </script>

    <style>
        .video-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .video-card:hover {
            transform: scale(1.03);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .video-card video {
            object-fit: cover;
            border-bottom: 2px solid #ddd;
        }

        .pagination .page-item.active .page-link {
            background-color: #212529;
            border-color: #212529;
        }
    </style>
</div>