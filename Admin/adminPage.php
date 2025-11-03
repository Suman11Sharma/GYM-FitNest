<?php
include "../database/user_authentication.php";
include "../database/db_connect.php";

// ✅ Get gym_id from session
$gym_id = $_SESSION['gym_id'] ?? 0;

// Initialize totals
$totalCustomers = 0;
$totalRevenue = 0;
$totalTrainers = 0;

// ✅ 1️⃣ Total Customers (of this gym)
$customerQuery = "SELECT COUNT(*) AS total 
                  FROM customers 
                  WHERE gym_id = $gym_id";
$customerResult = mysqli_query($conn, $customerQuery);
if ($customerResult) {
    $totalCustomers = (int)mysqli_fetch_assoc($customerResult)['total'];
}

// ✅ 2️⃣ Total Trainers (of this gym)
$trainerQuery = "SELECT COUNT(*) AS total 
                 FROM trainers 
                 WHERE gym_id = $gym_id";
$trainerResult = mysqli_query($conn, $trainerQuery);
if ($trainerResult) {
    $totalTrainers = (int)mysqli_fetch_assoc($trainerResult)['total'];
}

// ✅ 3️⃣ Total Revenue (for current month)
$currentYear = date('Y');
$currentMonth = date('m');

// --- Subscriptions ---
$subQuery = "SELECT COALESCE(SUM(amount), 0) AS total 
             FROM customer_subscriptions 
             WHERE gym_id = $gym_id 
             AND YEAR(start_date) = $currentYear 
             AND MONTH(start_date) = $currentMonth";
$subResult = mysqli_query($conn, $subQuery);
$subRevenue = ($subResult && $row = mysqli_fetch_assoc($subResult)) ? (float)$row['total'] : 0;

// --- Visitor Passes ---
$visitorQuery = "SELECT COALESCE(SUM(amount), 0) AS total 
                 FROM visitor_passes 
                 WHERE gym_id = $gym_id 
                 AND YEAR(created_at) = $currentYear 
                 AND MONTH(created_at) = $currentMonth";
$visitorResult = mysqli_query($conn, $visitorQuery);
$visitorRevenue = ($visitorResult && $row = mysqli_fetch_assoc($visitorResult)) ? (float)$row['total'] : 0;

// --- Trainer Bookings ---
$bookingQuery = "SELECT COALESCE(SUM(amount), 0) AS total 
                 FROM trainer_bookings 
                 WHERE gym_id = $gym_id 
                 AND YEAR(session_date) = $currentYear 
                 AND MONTH(session_date) = $currentMonth";
$bookingResult = mysqli_query($conn, $bookingQuery);
$bookingRevenue = ($bookingResult && $row = mysqli_fetch_assoc($bookingResult)) ? (float)$row['total'] : 0;

// ✅ Combine all revenue sources
$totalRevenue = $subRevenue + $visitorRevenue + $bookingRevenue;

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>FitNest</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="assets/css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/landing.css">

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<style>
    .admin-card {
        background-color: #f8f9fa;
        border-left: 4px solid #20677c;
        padding: 1rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .admin-title {
        font-size: 5rem;
        font-weight: 1200;
        margin-bottom: 0.5rem;
        color: #20677c;
    }

    .admin-card h6 {
        font-size: 2.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;

    }

    .admin-card p {
        margin: 0;
        font-size: 1.1rem;
    }

    .dashboard-summary {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 1rem;
    }

    .summary-card {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1rem;
        flex: 1;
        min-width: 180px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .summary-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .card-content {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .card-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .bg-gradient {
        background: linear-gradient(135deg, #20677c, #1b5e68);
    }

    .card-info {
        text-align: left;
    }

    .summary-title {
        font-size: 1.1rem;
        color: #20677c;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .summary-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #495057;
    }
</style>

<body class="sb-nav-fixed">
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
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="../index.php"><img src="uploads/logo_transparent.png" alt=""></a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>

    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu bg-dark">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Home</div>
                        <a class="nav-link" href="adminPage.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Management</div>
                        <!-- Payments -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapsePayments" aria-expanded="false" aria-controls="collapsePayments">
                            <div class="sb-nav-link-icon"><i class="fas fa-credit-card"></i></div>
                            Payments
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="collapsePayments" aria-labelledby="headingPayments" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Payments/payments.php">Payments</a>
                            </nav>
                        </div>

                        <!-- Paid-Ads  -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapsePaidAds" aria-expanded="false" aria-controls="collapsePaidAds">
                            <div class="sb-nav-link-icon"><i class="fas fa-ad"></i></div>
                            Paid-Ads
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="collapsePaidAds" aria-labelledby="headingPaidAds" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Paid-Ads/index.php">Index</a>
                                <a class="nav-link" href="Paid-Ads/create.php">Create</a>
                            </nav>
                        </div>

                        <!-- Visitor-Fees  -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseGymFees" aria-expanded="false" aria-controls="collapseGymFees">
                            <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                            Visitor-Fees
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="collapseGymFees" aria-labelledby="headingGymFees" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Gym-Fees/index.php">Index</a>
                                <a class="nav-link" href="Gym-Fees/create.php">Create</a>
                            </nav>
                        </div>


                        <!-- Gym-Subscriptions  -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseGymSubscriptions" aria-expanded="false" aria-controls="collapseGymSubscriptions">
                            <div class="sb-nav-link-icon"><i class="fas fa-calendar-alt"></i></div>
                            Gym-Subscriptions
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="collapseGymSubscriptions" aria-labelledby="headingGymSubscriptions" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Gym-Subscriptions/index.php">Index</a>
                                <a class="nav-link" href="Gym-Subscriptions/create.php">Create</a>
                            </nav>
                        </div>
                        <!-- Customer-Plans  -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseCustomerPlans" aria-expanded="false" aria-controls="collapseCustomerPlans">
                            <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                            Customer-Plans
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="collapseCustomerPlans" aria-labelledby="headingCustomerPlans" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Customer-Plans/index.php">Index</a>
                                <a class="nav-link" href="Customer-Plans/create.php">Create</a>
                            </nav>
                        </div>

                        <!-- Customer-Subscription  -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseCustomerSubscription" aria-expanded="false" aria-controls="collapseCustomerSubscription">
                            <div class="sb-nav-link-icon"><i class="fas fa-calendar-check"></i></div>
                            Customer-Subscription
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="collapseCustomerSubscription" aria-labelledby="headingCustomerSubscription" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Customer-Subscription/index.php">Index</a>
                            </nav>
                        </div>
                        <!-- Visitor_Passes -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseVisitorPasses" aria-expanded="false" aria-controls="collapseVisitorPasses">
                            <div class="sb-nav-link-icon"><i class="fas fa-ticket-alt"></i></div>
                            Visitor_Passes
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="collapseVisitorPasses" aria-labelledby="headingVisitorPasses" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Visitor_Passes/index.php">Index</a>
                            </nav>
                        </div>
                        <!-- Create Customers -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseCreateCustomers" aria-expanded="false" aria-controls="collapseCreateCustomers">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                            Create Customers
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="collapseCreateCustomers" aria-labelledby="headingCreateCustomers" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Create-Customers/index.php">Index</a>
                                <a class="nav-link" href="Create-Customers/create.php">Create</a>
                            </nav>
                        </div>
                        <!-- Create Trainer-->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseCreateTrainer" aria-expanded="false" aria-controls="collapseCreateTrainer">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                            Create Trainer
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="collapseCreateTrainer" aria-labelledby="headingCreateTrainer" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Create-Trainer/index.php">Index</a>
                                <a class="nav-link" href="Create-Trainer/create.php">Create</a>
                            </nav>
                        </div>

                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php
                    // Use name/fullname from session
                    if (isset($_SESSION['name'])) {
                        echo htmlspecialchars($_SESSION['name']);
                    } elseif (isset($_SESSION['fullname'])) {
                        echo htmlspecialchars($_SESSION['fullname']);
                    } else {
                        echo "<span class='text-muted'>Guest</span>";
                    }
                    ?>
                </div>
            </nav>
        </div>



        <div id="layoutSidenav_content">
            <main>
                <!-- Admin Card -->
                <div class="admin-card mb-4 p-3 rounded shadow-sm bg-light d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-2 admin-title">Admin</h6>
                        <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['name'] ?? 'Joe'); ?></p>
                        <p class="mb-0"><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email'] ?? 'hjjj@example.com'); ?></p>
                    </div>
                    <div>
                        <a href="Settings/create.php" class="btn btn-sm btn-our">
                            <i class="fas fa-cog me-1"></i> Settings
                        </a>
                    </div>
                </div>

                <!-- Dashboard Summary -->
                <div class="dashboard-summary mb-4 d-flex gap-3 flex-wrap">

                    <!-- Total Customers -->
                    <div class="summary-card flex-fill">
                        <div class="card-content">
                            <div class="card-icon bg-gradient">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="card-info">
                                <h6 class="summary-title">Total Customers</h6>
                                <p class="summary-number" id="total-customers"><?= $totalCustomers ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Revenue -->
                    <div class="summary-card flex-fill">
                        <div class="card-content">
                            <div class="card-icon bg-gradient">
                                <i class="fas fa-coins"></i>
                            </div>
                            <div class="card-info">
                                <h6 class="summary-title">Total Income (This Month)</h6>
                                <p class="summary-number" id="total-revenue">Rs. <?= number_format($totalRevenue, 2) ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Trainers -->
                    <div class="summary-card flex-fill">
                        <div class="card-content">
                            <div class="card-icon bg-gradient">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="card-info">
                                <h6 class="summary-title">Total Trainers</h6>
                                <p class="summary-number" id="total-trainers"><?= $totalTrainers ?></p>
                            </div>
                        </div>
                    </div>

                </div>

                <?php

                require("chartPage.php");
                require("userFetch.php");
                ?>


            </main>
            <footer class="py-4 bg-dark mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-evenly small">
                        <div class="text-muted"> &copy; 2025 FitNest. All Rights Reserved.</div>
                        <div>
                            <div class="text-muted">fitnest@gmail.com</div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="../assets/js/scripts.js"></script>
    <script src="../assets/js/datatables-simple-demo.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>