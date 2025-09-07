<?php require("../sidelayout.php"); ?>
<?php
include "../../database/db_connect.php";

// Pagination setup
$limit = 15;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = "WHERE 1"; // default
if (!empty($search)) {
    $searchEscaped = mysqli_real_escape_string($conn, $search);
    $where .= " AND (
        name LIKE '%$searchEscaped%' OR
        email LIKE '%$searchEscaped%' OR
        subject LIKE '%$searchEscaped%' OR
        status LIKE '%$searchEscaped%'
    )";
}

// Fetch data
$sql = "SELECT query_id, gym_id, name, email, contact, subject, message, status, reply, created_at 
        FROM contact_queries $where 
        ORDER BY created_at DESC 
        LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Total rows for pagination
$countSql = "SELECT COUNT(*) as total FROM contact_queries $where";
$countResult = mysqli_query($conn, $countSql);
$totalRows = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalRows / $limit);

// Fetch all data for JS (view modal)
$allResult = mysqli_query($conn, "SELECT * FROM contact_queries ORDER BY created_at DESC");
$allData = [];
while ($row = mysqli_fetch_assoc($allResult)) {
    $allData[] = [
        'query_id' => $row['query_id'],
        'gym_id' => $row['gym_id'],
        'name' => $row['name'],
        'email' => $row['email'],
        'contact' => $row['contact'],
        'subject' => $row['subject'],
        'message' => $row['message'],
        'status' => $row['status'],
        'reply' => $row['reply'],
        'created_at' => date('Y-m-d', strtotime($row['created_at'])) // only date
    ];
}
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container mt-4">
            <h3 class="mb-3">Contact Queries</h3>

            <!-- Search -->
            <form method="GET" class="d-flex justify-content-end mb-3">
                <input type="text" name="search" class="form-control me-2" placeholder="Search by name, email, subject, status..." value="<?= htmlspecialchars($search) ?>" style="max-width:400px;">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>SN</th>
                            <th>Gym ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Reply</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            $sn = $offset + 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                                $truncatedMessage = strlen($row['message']) > 30 ? substr($row['message'], 0, 30) . '...' : $row['message'];
                                echo "<tr>";
                                echo "<td>{$sn}</td>";
                                echo "<td>" . htmlspecialchars($row['gym_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['contact']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['subject']) . "</td>";
                                echo "<td>" . htmlspecialchars($truncatedMessage) . "</td>";
                                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['reply']) . "</td>";
                                echo "<td>" . date('Y-m-d', strtotime($row['created_at'])) . "</td>";
                                echo "<td>
                                        <button class='btn btn-sm btn-info' onclick='viewMessage({$row['query_id']})'>
                                            <i class='fas fa-eye'></i>
                                        </button>
                                        <a href='delete.php?id={$row['query_id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this message?');\">
                                            <i class='fas fa-trash'></i>
                                        </a>
                                    </td>";
                                echo "</tr>";
                                $sn++;
                            }
                        } else {
                            echo "<tr><td colspan='11' class='text-center'>No messages found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">Previous</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a></li>
                    <?php endfor; ?>
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Next</a></li>
                    <?php endif; ?>

                </ul>
            </nav>git add SuperAdmin/contactUs/index.php


            <!-- View Modal -->
            <div class="modal fade" id="viewContactModal" tabindex="-1" aria-labelledby="viewContactModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewContactModalLabel">Contact Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Full Name:</strong> <span id="modalFullName"></span></p>
                            <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                            <p><strong>Message:</strong> <span id="modalMessage"></span></p>
                            <p><strong>Received At:</strong> <span id="modalTimestamp"></span></p>
                        </div>
                        <p><strong>Created At:</strong> <span id="modalCreatedAt"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const contactData = <?= json_encode($allData) ?>;

            function viewMessage(id) {
                const data = contactData.find(item => item.query_id == id);
                if (!data) return;

                document.getElementById('modalGymID').textContent = data.gym_id;
                document.getElementById('modalName').textContent = data.name;
                document.getElementById('modalEmail').textContent = data.email;
                document.getElementById('modalContact').textContent = data.contact;
                document.getElementById('modalSubject').textContent = data.subject;
                document.getElementById('modalMessage').textContent = data.message;
                document.getElementById('modalStatus').textContent = data.status;
                document.getElementById('modalReply').textContent = data.reply;
                document.getElementById('modalCreatedAt').textContent = data.created_at;

                const modal = new bootstrap.Modal(document.getElementById('viewContactModal'));
                modal.show();
            }
        </script>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    </main>
</div>

<?php require("../assets/link.php"); ?>