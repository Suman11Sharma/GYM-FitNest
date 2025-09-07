<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container mt-4">
            <h3 class="mb-3">Ads Table</h3>

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
                            <th>AdsId</th>
                            <th>Image</th>
                            <th>Company Name</th>
                            <th>Duration</th>
                            <th>Visibility</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Replace this with actual DB fetch
                        $adsData = [
                            ['id' => 1, 'adsId' => 'AD001', 'image' => 'ad1.jpg', 'company' => 'Fitness Co.', 'duration' => '30 days', 'visibility' => 'Public'],
                            ['id' => 2, 'adsId' => 'AD002', 'image' => 'ad2.jpg', 'company' => 'Health Drinks', 'duration' => '60 days', 'visibility' => 'Private'],
                        ];

                        $sn = 1;
                        foreach ($adsData as $ad): ?>
                            <tr>
                                <td><?= $sn++ ?></td>
                                <td><?= htmlspecialchars($ad['adsId']) ?></td>
                                <td><img src="uploads/<?= htmlspecialchars($ad['image']) ?>" width="80" alt="Ad"></td>
                                <td><?= htmlspecialchars($ad['company']) ?></td>
                                <td><?= htmlspecialchars($ad['duration']) ?></td>
                                <td><?= htmlspecialchars($ad['visibility']) ?></td>
                                <td>
                                    <a href="edit.php?id=<?= $ad['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="delete.php?id=<?= $ad['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($adsData)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No ads found.</td>
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