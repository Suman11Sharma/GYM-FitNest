<?php
include "../../database/db_connect.php";

$query = "SELECT * FROM videos ORDER BY uploaded_at DESC";
$result = mysqli_query($conn, $query);

require("../sidelayout.php");
?>

<div id="layoutSidenav_content">
    <main class="container py-4">
        <h3 class="text-center fw-bold mb-4">🎬 Video Gallery</h3>

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
        <?php else: ?>
            <div class="alert alert-info text-center mt-4">
                No videos found.
            </div>
        <?php endif; ?>
    </main>

    <!-- Video Modal -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
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
            transform: scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .video-card video {
            object-fit: cover;
            border-bottom: 2px solid #ddd;
        }
    </style>
</div>