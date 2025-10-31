<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";
require("../sidelayout.php"); ?>
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
            <h3 class="mb-3">Ad Plans Table</h3>

            <!-- Toolbar: Add New + Search -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Add New Button -->
                <a href="create.php" class="btn btn-our">
                    <i class="fas fa-plus me-1"></i> Add New
                </a>

                <!-- Search Form -->
                <form method="GET" class="d-flex">
                    <div class="input-group" style="max-width: 400px;">
                        <input type="text" name="search" class="form-control"
                            placeholder="Search by name, description, or status..."
                            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>


            <!-- Ad Plans Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>SN</th>
                            <th>Plan Name</th>
                            <th>Duration (Days)</th>
                            <th>Price (NPR)</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        // Pagination setup
                        $limit = 15; // rows per page
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $offset = ($page - 1) * $limit;

                        // Search setup
                        $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
                        $where = "";
                        if (!empty($search)) {
                            $where = "WHERE name LIKE '%$search%' 
                                  OR description LIKE '%$search%' 
                                  OR status LIKE '%$search%'";
                        }

                        // Get total count for pagination
                        $countSql = "SELECT COUNT(*) AS total FROM ad_plans $where";
                        $countResult = mysqli_query($conn, $countSql);
                        $totalRows = mysqli_fetch_assoc($countResult)['total'];
                        $totalPages = ceil($totalRows / $limit);

                        // Fetch data
                        $sql = "SELECT plan_id, name, duration_days, price, description, status 
                            FROM ad_plans $where 
                            ORDER BY created_at DESC 
                            LIMIT $limit OFFSET $offset";
                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            $sn = $offset + 1;
                            while ($plan = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $sn++ . "</td>";
                                echo "<td>" . htmlspecialchars($plan['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($plan['duration_days']) . "</td>";
                                echo "<td>" . htmlspecialchars($plan['price']) . "</td>";
                                echo "<td>" . htmlspecialchars($plan['description']) . "</td>";
                                echo "<td>";
                                if ($plan['status'] === 'active') {
                                    echo "<span class='badge bg-success'>Active</span>";
                                } else {
                                    echo "<span class='badge bg-secondary'>Inactive</span>";
                                }
                                echo "</td>";
                                echo "<td class='text-center'>
                                    <a href='edit.php?id=" . $plan['plan_id'] . "' class='btn btn-sm btn-warning'>Edit</a>
                                    <a href='delete.php?id=" . $plan['plan_id'] . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this ad plan?');\">Delete</a>
                                  </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center '>No ad plans found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>

        <!-- FontAwesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    </main>

    <?php require("../assets/link.php"); ?>
</div>