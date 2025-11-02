<?php
include "../database/admin_authentication.php";
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

</style>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="../index.php"><img src="uploads/logo_transparent.png" alt=""></a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..."
                    aria-describedby="btnNavbarSearch" />
                <button class="btn btn-our" id="btnNavbarSearch" type="button"><i
                        class="fas fa-search"></i></button>
            </div>
        </form>
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
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
        <div class="modal fade" id="gymDetailModal" tabindex="-1" aria-labelledby="gymDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="gymDetailModalLabel">Gym Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <h6>🏋️ FitNest Gym, Pokhara</h6>
                        <p>FitNest Gym offers a complete fitness solution with modern equipment, professional trainers, and various classes including strength training, cardio, Zumba, and yoga.</p>
                        <ul>
                            <li>Opening Hours: 5:00 AM – 9:00 PM</li>
                            <li>Location: Lakeside, Pokhara</li>
                            <li>Contact: +977 9825160781</li>
                            <li>Email: info@fitnest.com</li>
                        </ul>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="renewModal" tabindex="-1" aria-labelledby="renewModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content needs-validation" novalidate>
                    <div class="modal-header">
                        <h5 class="modal-title" id="renewModalLabel">Renew Subscription</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" required>
                            <div class="invalid-feedback">Please enter your name.</div>
                        </div>

                        <!-- Contact -->
                        <div class="mb-3">
                            <label for="contact" class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="contact" pattern="^[0-9]{7,15}$" required>
                            <div class="invalid-feedback">Please enter a valid contact number.</div>
                        </div>

                        <!-- From Date -->
                        <div class="mb-3">
                            <label for="fromDate" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="fromDate" min="<?= date('Y-m-d'); ?>" required>
                            <div class="invalid-feedback">Start date must be today or later.</div>
                        </div>

                        <!-- To Date -->
                        <div class="mb-3">
                            <label for="toDate" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="toDate" required>
                            <div class="invalid-feedback">Please select a valid end date.</div>
                        </div>

                        <!-- Payment -->
                        <div class="mb-3">
                            <label for="payment" class="form-label">Payment Amount (Rs)</label>
                            <input type="number" class="form-control" id="payment" min="1" required>
                            <div class="invalid-feedback">Please enter the payment amount.</div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-our w-100">Submit Renewal</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="layoutSidenav_content">
            <main>

                <div class="card_header" id="recommendation">
                    <h1>Subscription available</h1>
                    <hr>
                </div>
                <div class="card-container">
                    <div class="card custom-card">
                        <div class="card-image">
                            <img src="uploads/gym.jpg" alt="Gym Name">
                        </div>

                        <div class="card-body card-body-custom">
                            <div>
                                <h5 class="card-title">Gym Name</h5>
                                <span class="text-success">Days Remaining 78</span>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-outline-primary btn-cool w-100 " data-bs-toggle="modal" data-bs-target="#gymDetailModal">
                                    More Detail
                                </a>
                                <a href="#" class="btn btn-primary btn-cool w-100 btn-bgcolor" data-bs-toggle="modal" data-bs-target="#renewModal">
                                    Renew
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card custom-card">
                        <div class="card-image">
                            <img src="uploads/gym.jpg" alt="Gym Name">
                        </div>

                        <div class="card-body card-body-custom">
                            <div>
                                <h5 class="card-title">Gym Name</h5>

                            </div>

                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-outline-primary btn-cool w-100 " data-bs-toggle="modal" data-bs-target="#gymDetailModal">
                                    More Detail
                                </a>
                                <a href="#" class="btn btn-primary btn-cool w-100 btn-bgcolor" data-bs-toggle="modal" data-bs-target="#renewModal">
                                    Renew
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card custom-card">
                        <div class="card-image">
                            <img src="uploads/gym.jpg" alt="Gym Name">
                        </div>

                        <div class="card-body card-body-custom">
                            <div>
                                <h5 class="card-title">Gym Name</h5>

                            </div>

                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-outline-primary btn-cool w-100 " data-bs-toggle="modal" data-bs-target="#gymDetailModal">
                                    More Detail
                                </a>
                                <a href="#" class="btn btn-primary btn-cool w-100 btn-bgcolor" data-bs-toggle="modal" data-bs-target="#renewModal">
                                    Renew
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card custom-card">
                        <div class="card-image">
                            <img src="uploads/gym.jpg" alt="Gym Name">
                        </div>

                        <div class="card-body card-body-custom">
                            <div>
                                <h5 class="card-title">Gym Name</h5>

                            </div>

                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-outline-primary btn-cool w-100 " data-bs-toggle="modal" data-bs-target="#gymDetailModal">
                                    More Detail
                                </a>
                                <a href="#" class="btn btn-primary btn-cool w-100 btn-bgcolor" data-bs-toggle="modal" data-bs-target="#renewModal">
                                    Renew
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card custom-card">
                        <div class="card-image">
                            <img src="uploads/gym.jpg" alt="Gym Name">
                        </div>

                        <div class="card-body card-body-custom">
                            <div>
                                <h5 class="card-title">Gym Name</h5>

                            </div>

                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-outline-primary btn-cool w-100 " data-bs-toggle="modal" data-bs-target="#gymDetailModal">
                                    More Detail
                                </a>
                                <a href="#" class="btn btn-primary btn-cool w-100 btn-bgcolor" data-bs-toggle="modal" data-bs-target="#renewModal">
                                    Renew
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card custom-card">
                        <div class="card-image">
                            <img src="uploads/gym.jpg" alt="Gym Name">
                        </div>

                        <div class="card-body card-body-custom">
                            <div>
                                <h5 class="card-title">Gym Name</h5>

                            </div>

                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-outline-primary btn-cool w-100 " data-bs-toggle="modal" data-bs-target="#gymDetailModal">
                                    More Detail
                                </a>
                                <a href="#" class="btn btn-primary btn-cool w-100 btn-bgcolor" data-bs-toggle="modal" data-bs-target="#renewModal">
                                    Renew
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card custom-card">
                        <div class="card-image">
                            <img src="uploads/gym.jpg" alt="Gym Name">
                        </div>

                        <div class="card-body card-body-custom">
                            <div>
                                <h5 class="card-title">Gym Name</h5>

                            </div>

                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-outline-primary btn-cool w-100 " data-bs-toggle="modal" data-bs-target="#gymDetailModal">
                                    More Detail
                                </a>
                                <a href="#" class="btn btn-primary btn-cool w-100 btn-bgcolor" data-bs-toggle="modal" data-bs-target="#renewModal">
                                    Renew
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card custom-card">
                        <div class="card-image">
                            <img src="uploads/gym.jpg" alt="Gym Name">
                        </div>

                        <div class="card-body card-body-custom">
                            <div>
                                <h5 class="card-title">Gym Name</h5>

                            </div>

                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-outline-primary btn-cool w-100 " data-bs-toggle="modal" data-bs-target="#gymDetailModal">
                                    More Detail
                                </a>
                                <a href="#" class="btn btn-primary btn-cool w-100 btn-bgcolor" data-bs-toggle="modal" data-bs-target="#renewModal">
                                    Renew
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card custom-card">
                        <div class="card-image">
                            <img src="uploads/gym.jpg" alt="Gym Name">
                        </div>

                        <div class="card-body card-body-custom">
                            <div>
                                <h5 class="card-title">Gym Name</h5>

                            </div>

                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-outline-primary btn-cool w-100 " data-bs-toggle="modal" data-bs-target="#gymDetailModal">
                                    More Detail
                                </a>
                                <a href="#" class="btn btn-primary btn-cool w-100 btn-bgcolor" data-bs-toggle="modal" data-bs-target="#renewModal">
                                    Renew
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card custom-card">
                        <div class="card-image">
                            <img src="uploads/gym.jpg" alt="Gym Name">
                        </div>

                        <div class="card-body card-body-custom">
                            <div>
                                <h5 class="card-title">Gym Name</h5>

                            </div>

                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-outline-primary btn-cool w-100 " data-bs-toggle="modal" data-bs-target="#gymDetailModal">
                                    More Detail
                                </a>
                                <a href="#" class="btn btn-primary btn-cool w-100 btn-bgcolor" data-bs-toggle="modal" data-bs-target="#renewModal">
                                    Renew
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
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