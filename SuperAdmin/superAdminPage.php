<?php
include "../database/admin_authentication.php";
include "../database/db_connect.php"; // ✅ mysqli connection
require("paymentCalculation.php");


// --- Get Current Month & Year ---
$currentMonth = date('m');
$currentYear = date('Y');

// --- Total Gyms ---
$sqlGyms = "SELECT COUNT(*) AS total_gyms FROM gyms";
$resGyms = mysqli_query($conn, $sqlGyms);
$totalGyms = mysqli_fetch_assoc($resGyms)['total_gyms'] ?? 0;

// --- Total Revenue (only current month) ---
$totalRevenue = 0;

// ✅ From customer_subscriptions (this month)
$sql1 = "SELECT SUM(amount) AS total FROM customer_subscriptions 
         WHERE payment_status = 'paid' 
         AND MONTH(created_at) = '$currentMonth' 
         AND YEAR(created_at) = '$currentYear'";
$res1 = mysqli_query($conn, $sql1);
$totalRevenue += floatval(mysqli_fetch_assoc($res1)['total'] ?? 0);

// ✅ From gym_subscriptions (this month)
$sql2 = "SELECT SUM(amount) AS total FROM gym_subscriptions 
         WHERE payment_status = 'paid' 
         AND MONTH(created_at) = '$currentMonth' 
         AND YEAR(created_at) = '$currentYear'";
$res2 = mysqli_query($conn, $sql2);
$totalRevenue += floatval(mysqli_fetch_assoc($res2)['total'] ?? 0);

// ✅ From trainer_bookings (this month)
$sql3 = "SELECT SUM(amount) AS total FROM trainer_bookings 
         WHERE payment_status = 'paid' 
         AND MONTH(created_at) = '$currentMonth' 
         AND YEAR(created_at) = '$currentYear'";
$res3 = mysqli_query($conn, $sql3);
$totalRevenue += floatval(mysqli_fetch_assoc($res3)['total'] ?? 0);

// ✅ From visitor_passes (this month)
$sql4 = "SELECT SUM(amount) AS total FROM visitor_passes 
         WHERE payment_status = 'paid' 
         AND MONTH(created_at) = '$currentMonth' 
         AND YEAR(created_at) = '$currentYear'";
$res4 = mysqli_query($conn, $sql4);
$totalRevenue += floatval(mysqli_fetch_assoc($res4)['total'] ?? 0);

// --- Total Active and Expired Subscriptions ---
$sqlSubs = "
    SELECT 
        SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) AS active_count,
        SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) AS expired_count
    FROM gym_subscriptions
";
$resSubs = mysqli_query($conn, $sqlSubs);
$subsData = mysqli_fetch_assoc($resSubs);
$totalActiveSubs = $subsData['active_count'] ?? 0;
$totalExpiredSubs = $subsData['expired_count'] ?? 0;
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
    .superadmin-card {
        background-color: #f8f9fa;
        border-left: 4px solid #20677c;
        padding: 1rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .superadmin-title {
        font-size: 5rem;
        font-weight: 1200;
        margin-bottom: 0.5rem;
        color: #20677c;
    }

    .superadmin-card h6 {
        font-size: 2.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;

    }

    .superadmin-card p {
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
                        <a class="nav-link" href="superAdminPage.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Management</div>
                        <!-- Ads -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseAds" aria-expanded="false" aria-controls="collapseAds">
                            <div class="sb-nav-link-icon"><i class="fas fa-ad"></i></div>
                            Ads
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="collapseAds" aria-labelledby="headingAds" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Ads/index.php">Index</a>
                                <a class="nav-link" href="Ads/create.php">Create</a>
                                <!-- <a class="nav-link" href="Ads/edit.php">Edit</a> -->
                            </nav>
                        </div>
                        <!-- Ads Plan -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseAdsPlan" aria-expanded="false" aria-controls="collapseAdsPlan">
                            <div class="sb-nav-link-icon"><i class="fas fa-ad"></i></div>
                            Ads-Plan
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="collapseAdsPlan" aria-labelledby="headingAdsPlan" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Ads-Plan/create.php">Create</a>
                                <a class="nav-link" href="Ads-Plan/index.php">Index</a>
                            </nav>
                        </div>
                        <!-- About Us -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseAboutUs" aria-expanded="false" aria-controls="collapseAboutUs">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            About Us
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="collapseAboutUs" aria-labelledby="headingAboutUs" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="AboutUs/index.php">Index</a>
                                <a class="nav-link" href="AboutUs/create.php">Create</a>
                                <!-- <a class="nav-link" href="AboutUs/edit.php">Edit</a> -->
                            </nav>
                        </div>


                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseContactUs" aria-expanded="false" aria-controls="collapseConatctus">
                            <div class="sb-nav-link-icon"><i class="fas fa-envelope"></i></div>
                            Contact us Message
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="collapseContactUs" aria-labelledby="headingContactUs" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="contactUs/index.php">Index</a>

                            </nav>
                        </div>


                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseRegister" aria-expanded="false" aria-controls="collapseRegister">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                            Register-Gym
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="collapseRegister" aria-labelledby="headingRegister" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Register/index.php">Index</a>
                                <a class="nav-link" href="Register/create.php">Create</a>
                                <!-- <a class="nav-link" href="Register/edit.php">Edit</a> -->
                            </nav>
                        </div>
                        <!-- Create-Users -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseUsers" aria-expanded="false" aria-controls="collapseUsers">
                            <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                            Create-Users
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="collapseUsers" aria-labelledby="headingUsers" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Create-Users/create.php">Create</a>
                                <a class="nav-link" href="Create-Users/index.php">Index</a>
                            </nav>
                        </div>
                        <!-- Saas Plans -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseSaasPlans" aria-expanded="false" aria-controls="collapseSaasPlans">
                            <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                            Saas Plans
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="collapseSaasPlans" aria-labelledby="headingSaasPlans" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="SaasPlans/create.php">Create</a>
                                <a class="nav-link" href="SaasPlans/index.php">Index</a>
                            </nav>
                        </div>
                        <!-- Renew Gym-->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseRenewGym" aria-expanded="false" aria-controls="collapseRenewGym">
                            <div class="sb-nav-link-icon"><i class="fas fa-calendar-alt"></i></div>
                            Renew Gym
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="collapseRenewGym" aria-labelledby="headingRenewGym" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Renew-Gym/create.php">Create</a>
                                <a class="nav-link" href="Renew-Gym/index.php">Index</a>
                            </nav>
                        </div>
                        <!-- Videos -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseVideos" aria-expanded="false" aria-controls="collapseVideos">
                            <div class="sb-nav-link-icon"><i class="fas fa-video"></i></div>
                            Videos
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="collapseVideos" aria-labelledby="headingVideos" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Videos/index.php">Index</a>
                                <a class="nav-link" href="Videos/create.php">Create</a>

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
                <!-- Superadmin Card -->
                <div class="superadmin-card mb-4 p-3 rounded shadow-sm bg-light">
                    <h6 class="mb-2 superadmin-title">Superadmin</h6>
                    <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['name'] ?? 'Joe'); ?></p>
                    <p class="mb-0"><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email'] ?? 'hjjj@example.com'); ?></p>
                </div>

                <!-- ✅ Dashboard Summary -->
                <div class="dashboard-summary mb-4 d-flex gap-3 flex-wrap">

                    <!-- Total Gyms -->
                    <div class="summary-card flex-fill">
                        <div class="card-content d-flex align-items-center">
                            <div class="card-icon bg-gradient">
                                <i class="fas fa-dumbbell"></i>
                            </div>
                            <div class="card-info">
                                <h6 class="summary-title">Total Gyms</h6>
                                <p class="summary-number"><?= $totalGyms ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Revenue (Current Month) -->
                    <div class="summary-card flex-fill">
                        <div class="card-content d-flex align-items-center">
                            <div class="card-icon bg-gradient">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="card-info">
                                <h6 class="summary-title">Income (<?= date('F Y') ?>)</h6>
                                <p class="summary-number">Rs. <?= number_format($totalRevenue, 2) ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Active/Expired Subscriptions -->
                    <div class="summary-card flex-fill">
                        <div class="card-content d-flex align-items-center">
                            <div class="card-icon bg-gradient">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="card-info">
                                <h6 class="summary-title">Gym Subscriptions</h6>
                                <p class="summary-number">
                                    <span class="text-success">Active: <?= $totalActiveSubs ?></span> |
                                    <span class="text-danger">Expired: <?= $totalExpiredSubs ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <?php require("analyticChart.php"); ?>
                <?php require("gymFetch.php"); ?>

                <!-- Analytics Section -->

            </main>
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