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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>FitNest | Customer Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/landing.css" />
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
    </style>
</head>

<body class="sb-nav-fixed">
    <!-- Navbar -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="../index.php"><img src="uploads/logo_transparent.png" alt="" height="30"></a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <ul class="navbar-nav ms-auto me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" data-bs-toggle="dropdown"
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
            <nav class="sb-sidenav accordion sb-sidenav-dark">
                <div class="sb-sidenav-menu bg-dark">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Home</div>
                        <a class="nav-link" href="#" id="dashboardLink">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Management</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapsePaidAds" aria-expanded="false" aria-controls="collapsePaidAds">
                            <div class="sb-nav-link-icon"><i class="fas fa-video"></i></div>
                            Workout Videos
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="collapsePaidAds" aria-labelledby="headingPaidAds" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Videos/index.php">Workout</a>
                            </nav>
                        </div>
                        <!--     Trainer Availability  -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseTrainerAvailability" aria-expanded="false" aria-controls="collapseTrainerAvailability">
                            <div class="sb-nav-link-icon"><i class="fas fa-calendar-day"></i></div>
                            Schedule
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="collapseTrainerAvailability" aria-labelledby="headingTrainerAvailability" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Trainer-Availability/index.php">Index</a>
                                <a class="nav-link" href="Trainer-Availability/create.php">Create</a>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php echo htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['name'] ?? 'Guest'); ?>
                </div>
            </nav>
        </div>

        <!-- Main content -->
        <div id="layoutSidenav_content">
            <main class="container-fluid px-4">

                <!-- Dashboard Section -->
                <div id="customerSection">
                    <h1 class="mt-4 mb-4">Trainer Dashboard</h1>
                    <div class="profile-header">
                        <img src="<?php echo $profile_image; ?>" alt="Profile Image">
                        <div class="profile-info">
                            <h3><?php echo htmlspecialchars($customer['full_name']); ?></h3>
                            <p>Member ID: <?php echo htmlspecialchars($customer['customer_id']); ?></p>
                            <p>Gym: <?php echo htmlspecialchars($gym['name']); ?> - <?php echo htmlspecialchars($gym['address']); ?></p>
                            <button class="btn-our renew-inline" id="openRenewBtn">Renew Membership</button>
                        </div>
                    </div>

                    <div class="info-card">
                        <h5 class="mb-3"><i class="fas fa-user-circle me-2 text-primary"></i>Personal Information</h5>
                        <div class="info-row">
                            <div class="info-item"><strong>Full Name:</strong> <?php echo htmlspecialchars($customer['full_name']); ?></div>
                            <div class="info-item"><strong>Gender:</strong> <?php echo htmlspecialchars($customer['gender']); ?></div>
                            <div class="info-item"><strong>Date of Birth:</strong> <?php echo htmlspecialchars($customer['date_of_birth']); ?></div>
                            <div class="info-item"><strong>Email:</strong> <?php echo htmlspecialchars($customer['email']); ?></div>
                            <div class="info-item"><strong>Phone:</strong> <?php echo htmlspecialchars($customer['phone']); ?></div>
                            <div class="info-item"><strong>Address:</strong> <?php echo htmlspecialchars($customer['address']); ?></div>
                        </div>
                    </div>

                    <div class="info-card">
                        <h5 class="mb-3"><i class="fas fa-dumbbell me-2 text-primary"></i>Membership Details</h5>
                        <div class="info-row">
                            <div class="info-item"><strong>Gym ID:</strong> <?php echo htmlspecialchars($customer['gym_id']); ?></div>
                            <div class="info-item"><strong>Join Date:</strong> <?php echo htmlspecialchars($customer['join_date']); ?></div>
                            <div class="info-item"><strong>Plan:</strong> N/A</div>
                            <div class="info-item"><strong>Expiry:</strong> N/A</div>
                        </div>
                        <div class="mt-4 text-center">
                            <h5>Status:</h5>
                            <?php $status_class = ($customer['status'] === 'active') ? 'bg-success' : 'bg-danger'; ?>
                            <span class="badge <?php echo $status_class; ?> px-4 py-2 fs-6">
                                <?php echo ucfirst($customer['status']); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Renew Membership Section -->
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
                <div class="container-fluid px-4 text-center text-muted small">
                    &copy; 2025 FitNest | fitnest@gmail.com
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/scripts.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const customerSection = document.getElementById("customerSection");
            const videosSection = document.getElementById("videosSection");
            const renewSection = document.getElementById("renewSection");

            document.getElementById("dashboardLink").addEventListener("click", e => {
                e.preventDefault();
                customerSection.classList.remove("hidden");
                videosSection.classList.add("hidden");
                renewSection.classList.add("hidden");
            });

            document.getElementById("videosLink").addEventListener("click", e => {
                e.preventDefault();
                customerSection.classList.add("hidden");
                videosSection.classList.remove("hidden");
                renewSection.classList.add("hidden");
            });

            document.getElementById("openRenewBtn").addEventListener("click", e => {
                e.preventDefault();
                customerSection.classList.add("hidden");
                videosSection.classList.add("hidden");
                renewSection.classList.remove("hidden");
            });
        });

        function goBackToDashboard() {
            document.getElementById("renewSection").classList.add("hidden");
            document.getElementById("customerSection").classList.remove("hidden");
        }
    </script>
</body>

</html>