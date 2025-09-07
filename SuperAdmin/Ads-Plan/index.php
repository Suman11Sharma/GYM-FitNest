<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container mt-4">
            <h3 class="mb-3">Ad Plans Table</h3>

            <!-- Add New Button -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="create.php" class="btn btn-our ms-3">
                    <i class="fas fa-plus me-1"></i> Add New
                </a>
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
                        // Sample Data (Replace with DB fetch)
                        $plans = [
                            ["id" => 1, "name" => "Basic Plan", "duration_days" => 15, "price" => 2000, "description" => "Short term plan", "status" => "active"],
                            ["id" => 2, "name" => "Premium Plan", "duration_days" => 30, "price" => 5000, "description" => "Full month plan", "status" => "inactive"],
                        ];

                        $sn = 1;
                        if (!empty($plans)):
                            foreach ($plans as $plan): ?>
                                <tr>
                                    <td><?= $sn++ ?></td>
                                    <td><?= htmlspecialchars($plan['name']) ?></td>
                                    <td><?= htmlspecialchars($plan['duration_days']) ?></td>
                                    <td><?= htmlspecialchars($plan['price']) ?></td>
                                    <td><?= htmlspecialchars($plan['description']) ?></td>
                                    <td>
                                        <?php if ($plan['status'] === 'active'): ?>
                                            <span class="badge bg-success"><?= ucfirst($plan['status']) ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?= ucfirst($plan['status']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="edit.php?id=<?= $plan['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="delete.php?id=<?= $plan['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this ad plan?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">No ad plans found.</td>
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