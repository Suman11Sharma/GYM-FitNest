<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container mt-4">
            <h3 class="mb-3">Advertisements Table</h3>

            <!-- Add New Button -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="create.php" class="btn btn-our ms-3">
                    <i class="fas fa-plus me-1"></i> Add New
                </a>
            </div>

            <!-- Ads Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>SN</th>
                            <th>Ad Type</th>
                            <th>Gym ID</th>
                            <th>Ads Name</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Link URL</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Sample data (replace with DB fetch)
                        $ads = [
                            ["id" => 1, "ad_type" => "gym", "gym_id" => "G001", "ads_name" => "Summer Blast", "title" => "Get Fit Now", "image_url" => "ad1.jpg", "link_url" => "https://example.com", "start_date" => "2025-09-01", "end_date" => "2025-09-30", "status" => "active"],
                            ["id" => 2, "ad_type" => "partner", "gym_id" => "", "ads_name" => "Partner Offer", "title" => "Join Today", "image_url" => "ad2.jpg", "link_url" => "https://example.com", "start_date" => "2025-09-05", "end_date" => "2025-10-05", "status" => "inactive"],
                        ];

                        $sn = 1;
                        if (!empty($ads)):
                            foreach ($ads as $ad): ?>
                                <tr>
                                    <td><?= $sn++ ?></td>
                                    <td><?= ucfirst($ad['ad_type']) ?></td>
                                    <td><?= htmlspecialchars($ad['gym_id'] ?: '-') ?></td>
                                    <td><?= htmlspecialchars($ad['ads_name']) ?></td>
                                    <td><?= htmlspecialchars($ad['title']) ?></td>
                                    <td>
                                        <?php if (!empty($ad['image_url'])): ?>
                                            <img src="../uploads/<?= htmlspecialchars($ad['image_url']) ?>" alt="Ad Image" style="width:80px; height:auto;">
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($ad['link_url'])): ?>
                                            <a href="<?= htmlspecialchars($ad['link_url']) ?>" target="_blank">Link</a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($ad['start_date']) ?></td>
                                    <td><?= htmlspecialchars($ad['end_date']) ?></td>
                                    <td>
                                        <?php if ($ad['status'] === 'active'): ?>
                                            <span class="badge bg-success"><?= ucfirst($ad['status']) ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?= ucfirst($ad['status']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="edit.php?id=<?= $ad['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="delete.php?id=<?= $ad['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this ad?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else: ?>
                            <tr>
                                <td colspan="11" class="text-center text-muted">No advertisements found.</td>
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