<?php
require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container mt-4">
            <h3 class="mb-3">Gyms Table</h3>

            <!-- Add New Button + Search -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="create.php" class="btn btn-our ms-3">
                    <i class="fas fa-plus me-1"></i> Add New
                </a>

                <!-- Search Form -->
                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2"
                        placeholder="Search by name, email, phone, address..."
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
                            <th>Gym ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Photo</th>
                            <th>Address</th>
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
                            $where = "WHERE name LIKE '%$searchEscaped%' 
                                  OR email LIKE '%$searchEscaped%'
                                  OR phone LIKE '%$searchEscaped%'
                                  OR address LIKE '%$searchEscaped%'";
                        }

                        // Fetch data with search & pagination
                        $sql = "SELECT gym_id, name, email, phone, address, image_url 
                            FROM gyms $where 
                            ORDER BY gym_id DESC 
                            LIMIT $limit OFFSET $offset";
                        $result = mysqli_query($conn, $sql);

                        // Count total rows for pagination
                        $countSql = "SELECT COUNT(*) as total FROM gyms $where";
                        $countResult = mysqli_query($conn, $countSql);
                        $totalRows = mysqli_fetch_assoc($countResult)['total'];
                        $totalPages = ceil($totalRows / $limit);

                        if (mysqli_num_rows($result) > 0) {
                            $sn = $offset + 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $sn++ . "</td>";
                                echo "<td>" . $row['gym_id'] . "</td>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                                echo "<td><img src='../../" . $row['image_url'] . "' width='80' class='img-thumbnail'></td>";
                                echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                                echo "<td>
                                    <a href='edit.php?id=" . $row['gym_id'] . "' class='btn btn-sm btn-warning'>Edit</a>
                                    <a href='delete.php?id=" . $row['gym_id'] . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this gym?');\">Delete</a>
                                  </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center p-3'>No gyms found</td></tr>";
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