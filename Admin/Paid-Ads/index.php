<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container mt-4">
            <h3 class="mb-3">Admin Ads Plans</h3>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="create.php" class="btn btn-our ms-3">
                    <i class="fas fa-plus"></i> Add New
                </a>
            </div>


            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Ad ID</th>
                                <th>Gym ID</th>
                                <th>Ads Plan</th>
                                <th>Image URL</th>
                                <th>Link URL</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Transaction ID</th>
                                <th>Approval Status</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td>1</td>
                                <td>GYM001</td>
                                <td>Gold Package</td>
                                <td>https://example.com/ad1.png</td>
                                <td>https://fitnest.com</td>
                                <td>2025-09-01</td>
                                <td>2025-09-30</td>
                                <td>2000</td>
                                <td>eSewa</td>
                                <td>TXN123456</td>
                                <td>Pending</td>
                                <td>Active</td>
                                <td>
                                    <a href="edit.php?id=1" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="delete.php?id=1" class="btn btn-sm btn-danger">Delete</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <?php require("../assets/link.php"); ?>
</div>