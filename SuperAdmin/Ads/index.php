<?php require("../sidelayout.php"); ?>

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

    <main>
        <div class="container mt-4">
            <h3 class="mb-3">Advertisements Table</h3>

            <!-- Add New Button + Search -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="create.php" class="btn btn-our ms-3">
                    <i class="fas fa-plus me-1"></i> Add New
                </a>

                <!-- Search Form -->
                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2"
                        placeholder="Search by ad type, ads name, title, status..."
                        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>SN</th>
                            <th>Ad Type</th>
                            <th>Gym ID</th>
                            <th>Ads Name</th>
                            <th>Title</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include "../../database/db_connect.php";

                        // Pagination setup
                        $limit = 15;
                        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
                        $offset = ($page - 1) * $limit;

                        // Search filter
                        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
                        $where = "";
                        if (!empty($search)) {
                            $searchEscaped = mysqli_real_escape_string($conn, $search);
                            $where = "WHERE ad_type LIKE '%$searchEscaped%' 
                          OR ads_name LIKE '%$searchEscaped%' 
                          OR title LIKE '%$searchEscaped%' 
                          OR status LIKE '%$searchEscaped%'
                          OR gym_id LIKE '%$searchEscaped%'";
                        }

                        // Fetch data from ads table
                        $sql = "SELECT ad_id, ad_type, gym_id, ads_name, title, image_url, start_date, end_date, status
                    FROM ads $where
                    ORDER BY ad_id DESC
                    LIMIT $limit OFFSET $offset";
                        $result = mysqli_query($conn, $sql);

                        // Count total rows for pagination
                        $countSql = "SELECT COUNT(*) as total FROM ads $where";
                        $countResult = mysqli_query($conn, $countSql);
                        $totalRows = mysqli_fetch_assoc($countResult)['total'];
                        $totalPages = ceil($totalRows / $limit);

                        if (mysqli_num_rows($result) > 0) {
                            $sn = $offset + 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $sn++ . "</td>";
                                echo "<td>" . htmlspecialchars($row['ad_type']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['gym_id'] ?: '-') . "</td>";
                                echo "<td>" . htmlspecialchars($row['ads_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['start_date']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['end_date']) . "</td>";

                                // Status badge
                                echo "<td>";
                                if ($row['status'] === 'active') {
                                    echo "<span class='badge bg-success'>Active</span>";
                                } else {
                                    echo "<span class='badge bg-secondary'>Inactive</span>";
                                }
                                echo "</td>";

                                // Image column
                                echo "<td>";
                                if (!empty($row['image_url'])) {
                                    echo "<img src='../../" . htmlspecialchars($row['image_url']) . "' width='80' height='60' class='img-thumbnail' style='object-fit:cover'>";
                                } else {
                                    echo "<span class='text-muted'>No Image</span>";
                                }
                                echo "</td>";

                                echo "<td>
                            <a href='edit.php?id=" . $row['ad_id'] . "' class='btn btn-sm btn-warning'>Edit</a>
                            <a href='delete.php?id=" . $row['ad_id'] . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this ad?');\">Delete</a>
                          </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10' class='text-center p-3'>No advertisements found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>


            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">Previous</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>

        </div>

        <!-- FontAwesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    </main>


    <?php require("../assets/link.php"); ?>
</div>