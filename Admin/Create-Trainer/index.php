<?php
require("../sidelayout.php");
include "../../database/db_connect.php";

// Fetch trainers
$query = "SELECT * FROM trainers ORDER BY trainer_id DESC";
$result = mysqli_query($conn, $query);
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container mt-4">
            <h4 class="mb-3">Trainer List</h4>

            <!-- ✅ Top Controls -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="create.php" class="btn btn-our ms-3">
                    <i class="fas fa-plus me-1"></i> Add New
                </a>

                <form method="GET" class="d-flex" style="max-width: 300px;">
                    <input type="text" name="search" class="form-control me-2"
                        placeholder="Search by name, email, phone, address"
                        value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- Table -->

            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Trainer ID</th>
                            <th>Gym ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Specialization</th>
                            <th>Rate/Session</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php $i = 1;
                            while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= htmlspecialchars($row['trainer_id']); ?></td>
                                    <td><?= htmlspecialchars($row['gym_id']); ?></td>
                                    <td><?= htmlspecialchars($row['name']); ?></td>
                                    <td><?= htmlspecialchars($row['email']); ?></td>
                                    <td><?= htmlspecialchars($row['phone']); ?></td>
                                    <td><?= htmlspecialchars($row['specialization']); ?></td>
                                    <td><?= htmlspecialchars($row['rate_per_session']); ?></td>
                                    <td>
                                        <a href="edit.php?id=<?= $row['trainer_id']; ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="delete.php?id=<?= $row['trainer_id']; ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this trainer?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted">No trainers found.</td>
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