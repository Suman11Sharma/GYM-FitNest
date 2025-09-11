<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container mt-4">
            <h3 class="mb-3">Customer Plans Table</h3>

            <!-- Add New Button -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="create.php" class="btn btn-our ms-3">
                    <i class="fas fa-plus me-1"></i> Add New
                </a>
            </div>

            <!-- Customer Plans Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Plan ID</th>
                            <th>Gym ID</th>
                            <th>Plan Name</th>
                            <th>Duration (Days)</th>
                            <th>Amount (NPR)</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Sample Data (Replace with DB fetch)
                        $customerPlans = [
                            ["id" => 1, "gym_id" => "GYM001", "plan_name" => "Monthly Visitor", "duration_days" => 30, "amount" => 1500],
                            ["id" => 2, "gym_id" => "GYM002", "plan_name" => "Quarterly Pass", "duration_days" => 90, "amount" => 4000],
                        ];

                        $sn = 1;
                        if (!empty($customerPlans)):
                            foreach ($customerPlans as $plan): ?>
                                <tr>
                                    <td><?= $sn++ ?></td>
                                    <td><?= htmlspecialchars($plan['gym_id']) ?></td>
                                    <td><?= htmlspecialchars($plan['plan_name']) ?></td>
                                    <td><?= htmlspecialchars($plan['duration_days']) ?></td>
                                    <td><?= htmlspecialchars($plan['amount']) ?></td>
                                    <td class="text-center">
                                        <a href="edit.php?id=<?= $plan['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="delete.php?id=<?= $plan['id'] ?>" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this plan?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">No customer plans found.</td>
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