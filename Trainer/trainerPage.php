<?php
include "../database/admin_authentication.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>FitNest | Trainer Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/landing.css">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <style>
        body {
            background-color: #f4f6f9;
        }

        .hidden {
            display: none;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-top: 1rem;
            margin-bottom: 1.5rem;
        }

        .profile-header img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #20677c;
        }

        .renew-card {
            border-radius: 10px;
            background: #fff;
            padding: 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .renew-card .card-header {
            background: #343a40;
            color: #fff;
            padding: 1rem 1.25rem;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <!-- Top Navbar -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="#"><img src="uploads/logo_transparent.png" alt="" height="30"></a>
        <button class="btn btn-link btn-sm" id="sidebarToggle"><i class="fas fa-bars"></i></button>
        <ul class="navbar-nav ms-auto me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" data-bs-toggle="dropdown"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <div id="layoutSidenav">
        <!-- Sidebar -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark">
                <div class="sb-sidenav-menu bg-dark">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Home</div>
                        <a class="nav-link" href="#" id="dashboardLink"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
                        <a class="nav-link" href="#" id="videosLink"><i class="fas fa-video me-2"></i>Videos</a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?= htmlspecialchars($_SESSION['name'] ?? $_SESSION['fullname'] ?? 'Guest'); ?>
                </div>
            </nav>
        </div>

        <!-- Main content -->
        <div id="layoutSidenav_content">
            <main class="container-fluid px-4">

                <!-- Dashboard / Trainer Details -->
                <div id="trainerSection">
                    <h1 class="mt-4 mb-4">Trainer Dashboard</h1>
                    <div class="profile-header"> <img src="uploads/profile/demo_user.jpg" alt="Profile Image" onerror="this.src='https://cdn-icons-png.flaticon.com/512/149/149071.png'">
                        <div class="profile-info">
                            <h3>John Doe</h3>
                            <p>Member ID: CUST001</p>
                            <p>Gym: FitNest - Pokhara</p>
                            <button class="btn-our renew-inline" id="openRenewBtn" title="Renew Membership">Renew Membership</button>
                        </div>
                    </div>
                    <div class="info-card">
                        <h5 class="mb-3"><i class="fas fa-user-circle me-2 text-primary"></i>Personal Information</h5>
                        <div class="info-row">
                            <div class="info-item"><strong>Full Name:</strong> John Doe</div>
                            <div class="info-item"><strong>Gender:</strong> Male</div>
                            <div class="info-item"><strong>Date of Birth:</strong> 1998-05-22</div>
                            <div class="info-item"><strong>Email:</strong> johndoe@gmail.com</div>
                            <div class="info-item"><strong>Phone:</strong> +977-9800000000</div>
                            <div class="info-item"><strong>Address:</strong> Pokhara, Nepal</div>
                        </div>
                    </div>
                    <div class="info-card">
                        <h5 class="mb-3"><i class="fas fa-dumbbell me-2 text-primary"></i>Membership Details</h5>
                        <div class="info-row">
                            <div class="info-item"><strong>Gym ID:</strong> GYM001</div>
                            <div class="info-item"><strong>Join Date:</strong> 2023-08-12</div>
                            <div class="info-item"><strong>Plan:</strong> Monthly</div>
                            <div class="info-item"><strong>Expiry:</strong> 2025-04-30</div>
                        </div> <!-- Keep the final Status badge here (as requested) -->
                        <div class="mt-4 text-center">
                            <h5>Status:</h5> <span class="badge bg-success px-4 py-2 fs-6">Active</span>
                        </div>
                    </div>
                </div>


                <!-- Videos -->
                <div id="videosSection" class="hidden">
                    <!-- videos.php content will be loaded here -->
                </div>

                <!-- Renew Section -->
                <div id="renewSection" class="hidden">
                    <main class="container mt-4">
                        <div class="card shadow-lg border-0 rounded-3">
                            <!-- Header with back button -->
                            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                <h4 class="mb-0 flex-grow-1 text-center">Renew Gym Membership</h4>
                                <!-- Back button: hides renewSection and shows dashboard -->
                                <button type="button" class="btn btn-light btn-sm border ms-3" title="Back to Dashboard" onclick="goBackToDashboard()">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                            </div>

                            <!-- Form -->
                            <div class="card-body p-4">
                                <form action="renew_store.php" method="POST" class="needs-validation" novalidate>

                                    <!-- Gym ID -->
                                    <div class="mb-3">
                                        <label for="gym_id" class="form-label">Gym ID</label>
                                        <input type="text" class="form-control" id="gym_id" name="gym_id" required value="GYM001">
                                        <div class="invalid-feedback">Please enter Gym ID.</div>
                                    </div>

                                    <!-- Plan Name -->
                                    <div class="mb-3">
                                        <label for="plan_name" class="form-label">Plan Name</label>
                                        <select id="plan_name" name="plan_name" class="form-select" required>
                                            <option value="" disabled selected>-- Select Plan --</option>
                                            <option value="monthly">Monthly</option>
                                            <option value="quarterly">Quarterly</option>
                                            <option value="yearly">Yearly</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a plan.</div>
                                    </div>

                                    <!-- Start Date -->
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label">Start Date</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                                        <div class="invalid-feedback">Please select a start date.</div>
                                    </div>

                                    <!-- End Date -->
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label">End Date</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                                        <div class="invalid-feedback">Please select an end date.</div>
                                    </div>

                                    <!-- Amount -->
                                    <div class="mb-3">
                                        <label for="amount" class="form-label">Amount (NPR)</label>
                                        <input type="number" class="form-control" id="amount" name="amount" min="0" required>
                                        <div class="invalid-feedback">Please enter a valid amount.</div>
                                    </div>

                                    <!-- Payment Status -->
                                    <div class="mb-3">
                                        <label for="payment_status" class="form-label">Payment Status</label>
                                        <select class="form-select" id="payment_status" name="payment_status" required>
                                            <option value="" disabled selected>-- Select Status --</option>
                                            <option value="pending">Pending</option>
                                            <option value="paid">Paid</option>
                                            <option value="failed">Failed</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a payment status.</div>
                                    </div>

                                    <!-- Transaction ID -->
                                    <div class="mb-3">
                                        <label for="transaction_id" class="form-label">Transaction ID</label>
                                        <input type="text" class="form-control" id="transaction_id" name="transaction_id" required>
                                        <div class="invalid-feedback">Please enter transaction ID.</div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-our px-5 py-2">Submit</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </main>
                </div>
            </main>

            <footer class="py-4 bg-dark mt-auto">
                <div class="container-fluid text-center text-white small">&copy; 2025 FitNest</div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/scripts.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dashboard = document.getElementById('trainerSection');
            const videos = document.getElementById('videosSection');
            const renew = document.getElementById('renewSection');

            document.getElementById('dashboardLink').addEventListener('click', () => {
                dashboard.classList.remove('hidden');
                videos.classList.add('hidden');
                renew.classList.add('hidden');
            });

            document.getElementById('videosLink').addEventListener('click', () => {
                dashboard.classList.add('hidden');
                videos.classList.remove('hidden');
                renew.classList.add('hidden');

                // Load videos dynamically from videos.php
                fetch('videos.php')
                    .then(res => res.text())
                    .then(html => {
                        videos.innerHTML = html;
                    });
            });

            document.getElementById('openRenewBtn').addEventListener('click', () => {
                dashboard.classList.add('hidden');
                videos.classList.add('hidden');
                renew.classList.remove('hidden');
            });
        });

        function goBackToDashboard() {
            document.getElementById('renewSection').classList.add('hidden');
            document.getElementById('trainerSection').classList.remove('hidden');
        }
    </script>
</body>

</html>