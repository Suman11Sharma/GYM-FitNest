<?php require("../sidelayout.php"); ?>
<?php
include "../../database/db_connect.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // prevent SQL injection

    $query = "SELECT `query_id`, `gym_id`, `name`, `email`, `contact`, `subject`, `message`, `status`, `reply`, `created_at`, `updated_at` 
              FROM contact_queries 
              WHERE query_id = $id";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
    } else {
        die("<div class='container py-5'><div class='alert alert-danger'>No record found.</div></div>");
    }
} else {
    die("<div class='container py-5'><div class='alert alert-warning'>Invalid ID.</div></div>");
}
?>

<div id="layoutSidenav_content">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0"><i class="bi bi-envelope-open"></i> Contact Query Details</h2>
            <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
        </div>

        <div class="card shadow border-0">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong><i class="bi bi-person-fill"></i> Name:</strong><br>
                            <?= htmlspecialchars($data['name']) ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong><i class="bi bi-telephone-fill"></i> Contact:</strong><br>
                            <?= htmlspecialchars($data['contact']) ?>
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong><i class="bi bi-envelope-fill"></i> Email:</strong><br>
                            <?= htmlspecialchars($data['email']) ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong><i class="bi bi-tag-fill"></i> Subject:</strong><br>
                            <?= htmlspecialchars($data['subject']) ?>
                        </p>
                    </div>
                </div>

                <div class="mb-3">
                    <p><strong><i class="bi bi-chat-text-fill"></i> Message:</strong><br>
                        <?= nl2br(htmlspecialchars($data['message'])) ?>
                    </p>
                </div>

                <div class="mb-3">
                    <p><strong><i class="bi bi-info-circle-fill"></i> Status:</strong><br>
                        <?php if ($data['status'] === 'replied'): ?>
                            <span class="badge bg-success">Replied</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark"><?= ucfirst($data['status']) ?></span>
                        <?php endif; ?>
                    </p>
                </div>

                <?php if (!empty($data['reply'])): ?>
                    <div class="mb-3">
                        <p><strong><i class="bi bi-reply-fill"></i> Admin Reply:</strong><br>
                            <?= nl2br(htmlspecialchars($data['reply'])) ?>
                        </p>
                    </div>
                <?php endif; ?>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <p><small><strong>Created At:</strong> <?= htmlspecialchars($data['created_at']) ?></small></p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p><small><strong>Updated At:</strong> <?= htmlspecialchars($data['updated_at']) ?></small></p>
                    </div>
                </div>

                <!-- Reply Button -->
                <div class="text-end mt-3">
                    <a href="reply.php?id=<?= $data['query_id'] ?>" class="btn btn-primary mt-3">
                        Reply
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>
<?php require("../assets/link.php"); ?>