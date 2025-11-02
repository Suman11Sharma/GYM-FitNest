<?php
include "../database/admin_authentication.php";
include("../database/db_connect.php");

// --- Ensure user is logged in ---
if (!isset($_SESSION['customer_id']) && !isset($_SESSION['gym_id'])) {
    header("Location: ../login.php");
    exit();
}

$customer_id = intval($_SESSION['customer_id']);
$gym_id = intval($_SESSION['gym_id']);

// --- Fetch Customer Info ---
$customer_query = $conn->prepare("
    SELECT customer_id, gym_id, full_name, gender, email, phone, address, 
           date_of_birth, profile_image, join_date, status 
    FROM customers 
    WHERE customer_id = ? AND gym_id = ? LIMIT 1
");
$customer_query->bind_param("ii", $customer_id, $gym_id);
$customer_query->execute();
$customer_result = $customer_query->get_result();

if ($customer_result->num_rows === 0) {
    die("❌ No customer found for this gym.");
}
$customer = $customer_result->fetch_assoc();

$profile_image = !empty($customer['profile_image'])
    ? 'data:image/jpeg;base64,' . base64_encode($customer['profile_image'])
    : 'https://cdn-icons-png.flaticon.com/512/149/149071.png';

// --- Fetch Gym Info ---
$gym_query = $conn->prepare("
    SELECT gym_id, name, email, phone, address, description, opening_time, closing_time, image_url 
    FROM gyms WHERE gym_id = ? LIMIT 1
");
$gym_query->bind_param("i", $gym_id);
$gym_query->execute();
$gym_result = $gym_query->get_result();
$gym = $gym_result->fetch_assoc();

// --- Fetch Customer Subscriptions ---
$sub_query = "
    SELECT cs.subscription_id, cs.user_id, c.full_name, c.email, c.phone, 
           cs.plan_id, p.plan_name, cs.start_date, cs.end_date, cs.amount, 
           cs.payment_status, cs.transaction_id, cs.status AS subscription_status
    FROM customer_subscriptions cs
    JOIN customers c ON cs.user_id = c.customer_id
    LEFT JOIN customer_plans p ON cs.plan_id = p.plan_id
    WHERE cs.gym_id = ?
    ORDER BY cs.created_at DESC
";
$stmt = $conn->prepare($sub_query);
$stmt->bind_param("i", $gym_id);
$stmt->execute();
$subscriptions = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>FitNest | Customer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/landing.css" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f4f6f9;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin: 1rem 0 1.5rem 0;
        }

        .profile-header img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #20677c;
        }

        .info-card {
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .info-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .info-item {
            background: #f8f9fa;
            padding: 0.75rem 1rem;
            border-radius: 8px;
        }

        .info-item strong {
            color: #20677c;
            display: block;
        }

        .renew-inline {
            font-size: 0.9rem;
            border-radius: 12px;
            padding: 6px 18px;
        }

        table th,
        table td {
            vertical-align: middle;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="../index.php"><img src="uploads/logo_transparent.png" alt="" height="30"></a>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark">
                <div class="sb-sidenav-menu bg-dark">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Home</div>
                        <a class="nav-link" href="#" id="dashboardLink">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Management</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapsePaidAds" aria-expanded="false" aria-controls="collapsePaidAds">
                            <div class="sb-nav-link-icon"><i class="fas fa-ad"></i></div>Workout Videos
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePaidAds" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Videos/index.php">Workout</a>
                            </nav>
                        </div>
                        <!--     Trainer Booking  -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseTrainerBooking" aria-expanded="false" aria-controls="collapseTrainerBooking">
                            <div class="sb-nav-link-icon"><i class="fas fa-calendar-day"></i></div>
                            Trainer Booking
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="collapseTrainerBooking" aria-labelledby="headingTrainerBooking" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Trainer-Booking/index.php">Book</a>
                                <a class="nav-link" href="Trainer-Booking/bookedStatus.php">Booked Status</a>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?= htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['name'] ?? 'Guest'); ?>
                </div>
            </nav>
        </div>

        <!-- Feedback Modal -->
        <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-<?php echo ($_GET['status'] ?? '') === 'success' ? 'success' : 'danger'; ?> text-white">
                        <h5 class="modal-title" id="feedbackModalLabel">
                            <?php echo ($_GET['status'] ?? '') === 'success' ? 'Success' : 'Error'; ?>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php echo isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : ''; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-<?php echo ($_GET['status'] ?? '') === 'success' ? 'success' : 'danger'; ?>" data-bs-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Auto-trigger modal if feedback exists -->
        <?php if (isset($_GET['status']) && isset($_GET['msg'])): ?>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    var feedbackModal = new bootstrap.Modal(document.getElementById("feedbackModal"));
                    feedbackModal.show();

                    // When modal is closed, remove query params so it won't reopen on refresh
                    document.getElementById("feedbackModal").addEventListener("hidden.bs.modal", function() {
                        const url = new URL(window.location.href);
                        url.search = ""; // clear query string
                        window.history.replaceState({}, document.title, url);
                    });
                });
            </script>
        <?php endif; ?>

        <div id="layoutSidenav_content">
            <main class="container-fluid px-4">
                <h1 class="mt-4 mb-4">Customer Dashboard</h1>

                <!-- Customer Info -->
                <div class="profile-header">
                    <img src="<?= $profile_image ?>" alt="Profile Image">
                    <div>
                        <h3><?= htmlspecialchars($customer['full_name']); ?></h3>
                        <p>Member ID: <?= htmlspecialchars($customer['customer_id']); ?></p>
                        <p>Gym: <?= htmlspecialchars($gym['name']); ?> - <?= htmlspecialchars($gym['address']); ?></p>
                        <a href="renew.php" class="btn btn-our renew-inline">Renew Membership</a>
                    </div>
                </div>

                <div class="info-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">
                            <i class="fas fa-user-circle me-2 text-primary"></i>Personal Information
                        </h5>
                        <!-- Edit Button -->
                        <a href="edit_customer.php?customer_id=<?= $customer['customer_id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit Information">
                            <i class="fas fa-cog"></i> Edit
                        </a>
                    </div>
                    <div class="info-row">
                        <div class="info-item"><strong>Full Name:</strong> <?= htmlspecialchars($customer['full_name']); ?></div>
                        <div class="info-item"><strong>Gender:</strong> <?= htmlspecialchars($customer['gender']); ?></div>
                        <div class="info-item"><strong>Date of Birth:</strong> <?= htmlspecialchars($customer['date_of_birth']); ?></div>
                        <div class="info-item"><strong>Email:</strong> <?= htmlspecialchars($customer['email']); ?></div>
                        <div class="info-item"><strong>Phone:</strong> <?= htmlspecialchars($customer['phone']); ?></div>
                        <div class="info-item"><strong>Address:</strong> <?= htmlspecialchars($customer['address']); ?></div>

                    </div>
                </div>


                <!-- Customer Subscriptions Table -->
                <div class="info-card">
                    <h5 class="mb-3"><i class="fas fa-dumbbell me-2 text-primary"></i>Subscriptions</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Customer</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Plan ID</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Amount</th>
                                    <th>Payment Status</th>
                                    <th>Transaction ID</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($subscriptions->num_rows > 0):
                                    $sn = 1;
                                    while ($sub = $subscriptions->fetch_assoc()):
                                ?>
                                        <tr>
                                            <td><?= $sn++; ?></td>
                                            <td><?= htmlspecialchars($sub['full_name']); ?></td>
                                            <td><?= htmlspecialchars($sub['email']); ?></td>
                                            <td><?= htmlspecialchars($sub['phone']); ?></td>
                                            <td><?= htmlspecialchars($sub['plan_name'] ?? 'N/A'); ?></td>
                                            <td><?= htmlspecialchars($sub['start_date']); ?></td>
                                            <td><?= htmlspecialchars($sub['end_date']); ?></td>
                                            <td>Rs. <?= htmlspecialchars($sub['amount']); ?></td>
                                            <td>
                                                <span class="badge <?= $sub['payment_status'] === 'paid' ? 'bg-success' : ($sub['payment_status'] === 'pending' ? 'bg-warning text-dark' : 'bg-danger'); ?>">
                                                    <?= ucfirst($sub['payment_status']); ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($sub['transaction_id']); ?></td>
                                            <td>
                                                <span class="badge <?= $sub['subscription_status'] === 'active' ? 'bg-success' : 'bg-secondary'; ?>">
                                                    <?= ucfirst($sub['subscription_status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endwhile;
                                else: ?>
                                    <tr>
                                        <td colspan="11" class="text-center text-muted">No subscriptions found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </main>

            <footer class="py-4 bg-dark mt-auto text-center text-white small">
                &copy; 2025 FitNest | fitnest@gmail.com
            </footer>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>