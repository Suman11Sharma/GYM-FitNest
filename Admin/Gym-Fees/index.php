<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container mt-4">
            <h3 class="mb-3">Gym Fees Table</h3>

            <!-- Add New Button -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="create.php" class="btn btn-our ms-3">
                    <i class="fas fa-plus me-1"></i> Add New
                </a>
            </div>

            <!-- Gym Fees Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Fee ID</th>
                            <th>Gym ID</th>
                            <th>Visitor Fee (NPR)</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Sample Data (Replace with DB fetch)
                        $fees = [
                            ["id" => 1, "gym_id" => "GYM001", "visitor_fee" => 500],
                            ["id" => 2, "gym_id" => "GYM002", "visitor_fee" => 800],
                        ];

                        $sn = 1;
                        if (!empty($fees)):
                            foreach ($fees as $fee): ?>
                                <tr>
                                    <td><?= $sn++ ?></td>
                                    <td><?= htmlspecialchars($fee['gym_id']) ?></td>
                                    <td><?= htmlspecialchars($fee['visitor_fee']) ?></td>
                                    <td class="text-center">
                                        <a href="edit.php?id=<?= $fee['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="delete.php?id=<?= $fee['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">No gym fees found.</td>
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