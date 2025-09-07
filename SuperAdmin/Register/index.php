<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container mt-4">
            <h3 class="mb-3">Gyms Table</h3>

            <!-- Add New Button -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="create.php" class="btn btn-our ms-3">
                    <i class="fas fa-plus me-1"></i> Add New
                </a>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>SN</th>
                            <th>Gym ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Photo</th>
                            <th>Address</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Replace this with actual DB fetch
                        $gymsData = [
                            [
                                'id' => 1,
                                'gym_id' => 'GYM001',
                                'name' => 'Everest Fitness',
                                'email' => 'info@everestfitness.com',
                                'phone' => '9800000001',
                                'photo' => 'gym1.jpg',
                                'address' => 'Lakeside, Pokhara',
                                'latitude' => '28.2096',
                                'longitude' => '83.9856'
                            ],
                            [
                                'id' => 2,
                                'gym_id' => 'GYM002',
                                'name' => 'Himalayan Gym',
                                'email' => 'contact@himalayangym.com',
                                'phone' => '9800000002',
                                'photo' => 'gym2.jpg',
                                'address' => 'Chipledhunga, Pokhara',
                                'latitude' => '28.2150',
                                'longitude' => '83.9810'
                            ],
                        ];

                        $sn = 1;
                        foreach ($gymsData as $gym): ?>
                            <tr>
                                <td><?= $sn++ ?></td>
                                <td><?= htmlspecialchars($gym['gym_id']) ?></td>
                                <td><?= htmlspecialchars($gym['name']) ?></td>
                                <td><?= htmlspecialchars($gym['email']) ?></td>
                                <td><?= htmlspecialchars($gym['phone']) ?></td>
                                <td>
                                    <img src="uploads/<?= htmlspecialchars($gym['photo']) ?>" width="80" alt="Gym Photo">
                                </td>
                                <td><?= htmlspecialchars($gym['address']) ?></td>
                                <td><?= htmlspecialchars($gym['latitude']) ?></td>
                                <td><?= htmlspecialchars($gym['longitude']) ?></td>
                                <td>
                                    <a href="edit.php?id=<?= $gym['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="delete.php?id=<?= $gym['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($gymsData)): ?>
                            <tr>
                                <td colspan="10" class="text-center">No gyms found.</td>
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