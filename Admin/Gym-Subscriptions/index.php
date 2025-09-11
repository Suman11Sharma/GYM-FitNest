<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container mt-4">
            <h3 class="mb-3">Gym Subscriptions Table</h3>

            <!-- Add New Button -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="create.php" class="btn btn-our ms-3">
                    <i class="fas fa-plus me-1"></i> Add New
                </a>
            </div>

            <!-- Gym Subscriptions Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Subscription ID</th>
                            <th>Gym ID</th>
                            <th>Plan Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Amount (NPR)</th>
                            <th>Payment Status</th>
                            <th>Transaction ID</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Sample Data (Replace with DB fetch)
                        $subscriptions = [
                            [
                                "id" => 1,
                                "gym_id" => "GYM001",
                                "plan_name" => "Monthly",
                                "start_date" => "2025-01-01",
                                "end_date" => "2025-01-31",
                                "amount" => 2000,
                                "payment_status" => "Paid",
                                "transaction_id" => "TXN123456"
                            ],
                            [
                                "id" => 2,
                                "gym_id" => "GYM002",
                                "plan_name" => "Quarterly",
                                "start_date" => "2025-02-01",
                                "end_date" => "2025-04-30",
                                "amount" => 5000,
                                "payment_status" => "Pending",
                                "transaction_id" => "TXN654321"
                            ]
                        ];

                        $sn = 1;
                        if (!empty($subscriptions)):
                            foreach ($subscriptions as $sub): ?>
                                <tr>
                                    <td><?= $sn++ ?></td>
                                    <td><?= htmlspecialchars($sub['gym_id']) ?></td>
                                    <td><?= htmlspecialchars($sub['plan_name']) ?></td>
                                    <td><?= htmlspecialchars($sub['start_date']) ?></td>
                                    <td><?= htmlspecialchars($sub['end_date']) ?></td>
                                    <td><?= htmlspecialchars($sub['amount']) ?></td>
                                    <td><?= htmlspecialchars($sub['payment_status']) ?></td>
                                    <td><?= htmlspecialchars($sub['transaction_id']) ?></td>
                                    <td class="text-center">
                                        <a href="edit.php?id=<?= $sub['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="delete.php?id=<?= $sub['id'] ?>" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this subscription?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else: ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted">No subscriptions found.</td>
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