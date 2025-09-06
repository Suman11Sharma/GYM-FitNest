<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container mt-4">
            <h3 class="mb-3">Users Table</h3>

            <!-- Search Form and Add Button -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Search form -->
                <form class="form-inline d-flex" id="searchForm" onsubmit="return false;">
                    <div class="input-group">
                        <input
                            id="searchInput"
                            class="form-control"
                            type="text"
                            placeholder="Search for..."
                            aria-label="Search for..."
                            aria-describedby="btnNavbarSearch" />
                        <button class="btn btn-our" id="btnNavbarSearch" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Add User Button -->
                <a href="create.php" class="btn btn-our ms-3">
                    <i class="fas fa-plus me-1"></i> Add New User
                </a>
            </div>

            <!-- Table -->
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>SN</th>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <!-- PHP or JS will populate rows -->
                    <?php
                    // Example static rows (replace with DB query)
                    $users = [
                        ["id" => 1, "name" => "Suman Poudel", "email" => "suman@example.com", "phone" => "9812345678", "role" => "Superadmin"],
                        ["id" => 2, "name" => "Admin User", "email" => "admin@example.com", "phone" => "9800000000", "role" => "Admin"],
                    ];
                    $sn = 1;
                    foreach ($users as $user) {
                        echo "<tr>
                                <td>{$sn}</td>
                                <td>{$user['id']}</td>
                                <td>{$user['name']}</td>
                                <td>{$user['email']}</td>
                                <td>{$user['phone']}</td>
                                <td>{$user['role']}</td>
                                <td>
                                    <a href='edit.php?id={$user['id']}' class='btn btn-sm btn-primary'><i class='fas fa-edit'></i></a>
                                    <a href='delete.php?id={$user['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this user?');\"><i class='fas fa-trash'></i></a>
                                </td>
                            </tr>";
                        $sn++;
                    }
                    ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center" id="pagination">
                    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>

        <!-- FontAwesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    </main>

    <?php require("../assets/link.php"); ?>
</div>