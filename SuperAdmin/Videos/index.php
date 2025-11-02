<?php
include("../../database/db_connect.php");

// --- Pagination Settings ---
$limit = 15;
$page = isset($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// --- Search ---
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchQuery = "";
$searchParams = [];

if ($search !== '') {
    $searchQuery = "WHERE title LIKE ? OR description LIKE ? OR status LIKE ?";
    $like = "%$search%";
    $searchParams = [$like, $like, $like];
}

// --- Count total videos ---
$countSql = "SELECT COUNT(*) AS total FROM videos $searchQuery";
$countStmt = $conn->prepare($countSql);
if (!empty($searchParams)) $countStmt->bind_param("sss", ...$searchParams);
$countStmt->execute();
$totalResult = $countStmt->get_result();
$totalVideos = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalVideos / $limit);

// --- Fetch paginated videos ---
$sql = "SELECT video_id, title, filename, description, status, uploaded_at 
        FROM videos 
        $searchQuery 
        ORDER BY uploaded_at DESC 
        LIMIT $limit OFFSET $offset";
$stmt = $conn->prepare($sql);
if (!empty($searchParams)) $stmt->bind_param("sss", ...$searchParams);
$stmt->execute();
$result = $stmt->get_result();
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
    <main class="container mt-4">
        <h3 class="mb-3">Videos Table</h3>

        <!-- Add New + Search -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="create.php" class="btn btn-our ms-3">
                <i class="fas fa-plus me-1"></i> Add New
            </a>

            <form method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2"
                    placeholder="Search by title, description, status..."
                    value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover shadow-sm align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>SN</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Preview</th>
                        <th>Uploaded At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $sn = $offset + 1;
                        while ($row = $result->fetch_assoc()) {
                            $videoPath = "../../uploads/videos/" . htmlspecialchars($row['filename']);
                            echo "
                            <tr>
                                <td>{$sn}</td>
                                <td>" . htmlspecialchars($row['title']) . "</td>
                                <td>" . htmlspecialchars($row['description']) . "</td>
                                <td>
                                    <span class='badge " . ($row['status'] == 'active' ? 'bg-success' : 'bg-secondary') . "'>
                                        " . ucfirst($row['status']) . "
                                    </span>
                                </td>
                                <td class='text-center'>
                                    <button class='btn btn-sm btn-info' data-bs-toggle='modal' data-bs-target='#videoModal{$row['video_id']}'>
                                        <i class='fas fa-play'></i> Preview
                                    </button>

                                    <!-- Video Modal -->
                                    <div class='modal fade' id='videoModal{$row['video_id']}' tabindex='-1' aria-labelledby='videoModalLabel{$row['video_id']}' aria-hidden='true'>
                                        <div class='modal-dialog modal-dialog-centered modal-lg'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                    <h5 class='modal-title'>" . htmlspecialchars($row['title']) . "</h5>
                                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                </div>
                                                <div class='modal-body text-center'>
                                                <div class='ratio ratio-16x9'>
                                                        <video class='w-100 rounded shadow-sm' controls preload='metadata'>
                                                            <source src='$videoPath' type='video/mp4'>
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>" . htmlspecialchars($row['uploaded_at']) . "</td>
                                <td>
                                    <a href='edit.php?id={$row['video_id']}' class='btn btn-sm btn-warning'>
                                        <i class='fas fa-edit'></i>
                                    </a>
                                    <a href='delete.php?id={$row['video_id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this video?\")'>
                                        <i class='fas fa-trash'></i>
                                    </a>
                                </td>
                            </tr>";
                            $sn++;
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center text-muted'>No videos found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">Next</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>

    </main>
</div>

<?php require("../assets/link.php"); ?>