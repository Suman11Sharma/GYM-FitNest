<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container mt-4">
            <h3 class="mb-3">Users Table</h3>

            <!-- Add New Button -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="create.php" class="btn btn-our ms-3">
                    <i class="fas fa-plus me-1"></i> Add New
                </a>
            </div>

            <!-- Users Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>SN</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Gym ID</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Sample Data (Replace with DB fetch)
                        $users = [
                            ["id" => 1, "name" => "John Doe", "email" => "john@example.com", "phone" => "9800000000", "role" => "Admin", "gym_id" => "GYM101"],
                            ["id" => 2, "name" => "Jane Smith", "email" => "jane@example.com", "phone" => "9811111111", "role" => "Superadmin", "gym_id" => null],
                        ];

                        $sn = 1;
                        if (!empty($users)):
                            foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $sn++ ?></td>
                                    <td><?= htmlspecialchars($user['name']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= htmlspecialchars($user['phone']) ?></td>
                                    <td>
                                        <span class="badge <?= $user['role'] === 'Admin' ? 'bg-primary' : 'bg-secondary' ?>">
                                            <?= htmlspecialchars($user['role']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($user['role'] === 'Admin' && !empty($user['gym_id'])): ?>
                                            <?= htmlspecialchars($user['gym_id']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="edit.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="delete.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FontAwesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    </main>
    <?php require("../assets/link.php"); ?>
</div>