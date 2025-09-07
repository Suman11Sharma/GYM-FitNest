<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container mt-4">
            <h3 class="mb-3"></i>SaaS Plans Table</h3>

            <!-- Add New Button -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="create.php" class="btn btn-our ms-3">
                    <i class="fas fa-plus me-1"></i> Add New
                </a>
            </div>

            <!-- SaaS Plans Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>SN</th>
                            <th>Plan Name</th>
                            <th>Features</th>
                            <th>Amount (NPR)</th>
                            <th>Duration (Months)</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Sample Data (Replace with DB fetch)
                        $plans = [
                            ["id" => 1, "plan_name" => "Basic", "features" => "Feature A, Feature B", "amount" => 500, "duration_months" => 1],
                            ["id" => 2, "plan_name" => "Pro", "features" => "Feature A, Feature B, Feature C", "amount" => 1200, "duration_months" => 3],
                        ];

                        $sn = 1;
                        if (!empty($plans)):
                            foreach ($plans as $plan): ?>
                                <tr>
                                    <td><?= $sn++ ?></td>
                                    <td><?= htmlspecialchars($plan['plan_name']) ?></td>
                                    <td><?= htmlspecialchars($plan['features']) ?></td>
                                    <td><?= htmlspecialchars($plan['amount']) ?></td>
                                    <td><?= htmlspecialchars($plan['duration_months']) ?></td>
                                    <td class="text-center">
                                        <a href="edit.php?id=<?= $plan['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit me-1"></i>Edit</a>
                                        <a href="delete.php?id=<?= $plan['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this plan?');"><i class="fas fa-trash me-1"></i>Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">No plans found.</td>
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