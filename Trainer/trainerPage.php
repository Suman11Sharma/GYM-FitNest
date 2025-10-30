<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>FitNest | Trainer Dashboard</title>

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/landing.css">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <style>
        body {
            background-color: #f4f6f9;
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

        .profile-info h3 {
            margin-bottom: 0.3rem;
            font-weight: 600;
        }

        .profile-info p {
            color: #6c757d;
            margin-bottom: 0.3rem;
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

        .hidden {
            display: none;
        }

        /* Video cards grid */
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
            padding-bottom: 2rem;
        }

        .custom-card {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            background: #fff;
            display: flex;
            flex-direction: column;
        }

        .card-image video {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .card-body-custom {
            padding: 0.75rem;
        }

        /* Renew form card */
        .renew-card {
            border-radius: 10px;
            background: #fff;
            padding: 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .renew-card .card-header {
            background: #343a40;
            color: #fff;
            padding: 1rem 1.25rem;
        }

        .renew-card .card-body {
            padding: 1.25rem;
        }

        .renew-inline {
            font-size: 0.9rem;
            border-radius: 12px;
            padding: 6px 18px;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <!-- Top nav -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="#"><img src="uploads/logo_transparent.png" alt="" height="30"></a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle"><i
                class="fas fa-bars"></i></button>

        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." />
                <button class="btn btn-our" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
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
        <!-- Sidebar -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu bg-dark">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Home</div>
                        <a class="nav-link" href="#" id="dashboardLink">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>

                        <div class="sb-sidenav-menu-heading">Management</div>

                        <!-- Videos link: same-page anchor -->
                        <a class="nav-link" href="#" id="videosLink">
                            <div class="sb-nav-link-icon"><i class="fas fa-video"></i></div>
                            Videos
                        </a>

                        <!-- You said: don't add renew in sidebar. -->
                    </div>
                </div>

                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php
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

        <!-- Main content -->
        <div id="layoutSidenav_content">
            <main class="container-fluid px-4">

                <!-- Dashboard / Trainer Details -->
                <div id="trainerSection">
                    <h1 class="mt-4 mb-4">Trainer Dashboard</h1>

                    <div class="profile-header">
                        <img src="uploads/profile/demo_user.jpg" alt="Profile Image" onerror="this.src='https://cdn-icons-png.flaticon.com/512/149/149071.png'">
                        <div class="profile-info">
                            <h3>John Doe</h3>
                            <p>Member ID: CUST001</p>
                            <p>Gym: FitNest - Pokhara</p>

                            <!-- SMALL RENEW BUTTON (opens renew form inside layout) -->
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
                        </div>

                        <!-- Keep the final Status badge here (as requested) -->
                        <div class="mt-4 text-center">
                            <h5>Status:</h5>
                            <span class="badge bg-success px-4 py-2 fs-6">Active</span>
                        </div>
                    </div>
                </div>

                <!-- Videos Section (demo cards) -->
                <div id="videosSection" class="hidden">
                    <h1 class="mt-4">Workout Videos</h1>
                    <hr>
                    <div class="card-container">
                        <div class="card custom-card">
                            <div class="card-image">
                                <video controls preload="metadata">
                                    <source src="uploads/videos/video1.mp4" type="video/mp4">
                                </video>
                            </div>
                            <div class="card-body card-body-custom">
                                <h5 class="card-title">Upper Body Workout</h5>
                                <p class="card-text">Focus on chest, shoulders and triceps.</p>
                            </div>
                        </div>

                        <div class="card custom-card">
                            <div class="card-image">
                                <video controls preload="metadata">
                                    <source src="uploads/videos/video2.mp4" type="video/mp4">
                                </video>
                            </div>
                            <div class="card-body card-body-custom">
                                <h5 class="card-title">Cardio Training</h5>
                                <p class="card-text">Enhance stamina with guided cardio sessions.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Renew Membership Section (hidden by default) -->
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
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-evenly small">
                        <div class="text-muted">&copy; 2025 FitNest. All Rights Reserved.</div>
                        <div class="text-muted">fitnest@gmail.com</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- JS: remove duplicate bootstrap includes, keep one bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <script src="../assets/js/scripts.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const trainerSection = document.getElementById('trainerSection');
            const videosSection = document.getElementById('videosSection');
            const renewSection = document.getElementById('renewSection');

            const dashboardLink = document.getElementById('dashboardLink');
            const videosLink = document.getElementById('videosLink');
            const openRenewBtn = document.getElementById('openRenewBtn');
            const cancelRenewBtn = document.getElementById('cancelRenewBtn');

            // Dashboard link
            dashboardLink.addEventListener('click', function(e) {
                e.preventDefault();
                trainerSection.classList.remove('hidden');
                videosSection.classList.add('hidden');
                renewSection.classList.add('hidden');
                dashboardLink.classList.add('active');
                videosLink.classList.remove('active');
            });

            // Videos link
            videosLink.addEventListener('click', function(e) {
                e.preventDefault();
                trainerSection.classList.add('hidden');
                videosSection.classList.remove('hidden');
                renewSection.classList.add('hidden');
                videosLink.classList.add('active');
                dashboardLink.classList.remove('active');
            });

            // Open renew form (from small renew button)
            openRenewBtn.addEventListener('click', function(e) {
                e.preventDefault();
                trainerSection.classList.add('hidden');
                videosSection.classList.add('hidden');
                renewSection.classList.remove('hidden');

                // optionally prefill start_date with today
                const startDateInput = document.getElementById('start_date');
                if (startDateInput && !startDateInput.value) {
                    const today = new Date().toISOString().slice(0, 10);
                    startDateInput.value = today;
                }
            });

            // Cancel renew -> back to dashboard
            cancelRenewBtn.addEventListener('click', function() {
                renewSection.classList.add('hidden');
                trainerSection.classList.remove('hidden');
            });

            // Bootstrap-like validation for forms with .needs-validation
            (function() {
                'use strict'
                const forms = document.querySelectorAll('.needs-validation')
                Array.from(forms).forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
            })();
        });

        function goBackToDashboard() {
            document.getElementById('renewSection').classList.add('hidden');
            document.getElementById('trainerSection').classList.remove('hidden');
        }
    </script>
</body>

</html>