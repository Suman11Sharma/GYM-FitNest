<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container mt-4">
            <h3 class="mb-3">Customer-Subscription</h3>

            <!-- Add New Button -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="create.php" class="btn btn-our ms-3">
                    <i class="fas fa-plus me-1"></i> Add New
                </a>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover shadow-sm align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>SN</th>
                                <th>User ID</th>
                                <th>Plan ID</th>
                                <th>Gym ID</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Amount</th>
                                <th>Payment Status</th>
                                <th>Transaction ID</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Sample data (replace with DB fetch)
                            $subscriptions = [
                                ["id" => 1, "user_id" => 101, "plan_id" => 5, "gym_id" => 3, "start_date" => "2025-09-01", "end_date" => "2025-09-30", "amount" => 2500, "payment_status" => "paid", "transaction_id" => "TXN12345"],
                                ["id" => 2, "user_id" => 102, "plan_id" => 2, "gym_id" => 1, "start_date" => "2025-09-05", "end_date" => "2025-09-20", "amount" => 1500, "payment_status" => "pending", "transaction_id" => "TXN12346"],
                                ["id" => 3, "user_id" => 103, "plan_id" => 3, "gym_id" => 2, "start_date" => "2025-09-10", "end_date" => "2025-10-09", "amount" => 3000, "payment_status" => "failed", "transaction_id" => "TXN12347"],
                            ];

                            $sn = 1;
                            if (!empty($subscriptions)) :
                                foreach ($subscriptions as $sub) : ?>
                                    <tr>
                                        <td><?= $sn++ ?></td>
                                        <td><?= $sub['user_id'] ?></td>
                                        <td><?= $sub['plan_id'] ?></td>
                                        <td><?= $sub['gym_id'] ?></td>
                                        <td><?= $sub['start_date'] ?></td>
                                        <td><?= $sub['end_date'] ?></td>
                                        <td><?= $sub['amount'] ?></td>
                                        <td>
                                            <?php
                                            if ($sub['payment_status'] == 'paid') echo '<span class="badge bg-success">Paid</span>';
                                            elseif ($sub['payment_status'] == 'pending') echo '<span class="badge bg-warning text-dark">Pending</span>';
                                            else echo '<span class="badge bg-danger">Failed</span>';
                                            ?>
                                        </td>
                                        <td><?= $sub['transaction_id'] ?></td>
                                        <td class="text-center">
                                            <a href="edit.php?id=<?= $sub['id'] ?>" class="btn btn-sm btn-warning me-1">Edit</a>
                                            <a href="delete.php?id=<?= $sub['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this subscription?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else : ?>
                                <tr>
                                    <td colspan="10" class="text-center text-muted">No customer subscriptions found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>