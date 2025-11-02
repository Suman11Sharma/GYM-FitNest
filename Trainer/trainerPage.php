<?php
include "../database/admin_authentication.php";
include("../database/db_connect.php");

// --- Ensure trainer is logged in ---
if (!isset($_SESSION['trainer_id']) && !isset($_SESSION['gym_id'])) {
    header("Location: ../login.php");
    exit();
}

$trainer_id = intval($_SESSION['trainer_id']);
$gym_id = intval($_SESSION['gym_id']);

// --- Fetch Trainer Info ---
$query = $conn->prepare("
    SELECT trainer_id, gym_id, name, email, phone, specialization, rate_per_session, 
           created_at, updated_at, status 
    FROM trainers 
    WHERE trainer_id = ? AND gym_id = ? LIMIT 1
");
$query->bind_param("ii", $trainer_id, $gym_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    die("❌ No trainer found for this gym.");
}
$trainer = $result->fetch_assoc();

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
    <title>FitNest | Trainer Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../assets/css/styles.css" rel="stylesheet" />
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
                            data-bs-target="#collapseVideos" aria-expanded="false" aria-controls="collapseVideos">
                            <div class="sb-nav-link-icon"><i class="fas fa-video"></i></div>
                            My Videos
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseVideos" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Videos/index.php">Manage Videos</a>
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
                        <!--     Booking -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseBooking" aria-expanded="false" aria-controls="collapseBooking">
                            <div class="sb-nav-link-icon"><i class="fas fa-calendar-day"></i></div>
                            Bookings
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="collapseBooking" aria-labelledby="headingBooking data-bs-parent=" #sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Booking/index.php">Index</a>
                            </nav>
                        </div>

                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?= htmlspecialchars($trainer['name']); ?>
                </div>
            </nav>
        </div>

        <!-- Main content -->
        <div id="layoutSidenav_content">
            <main class="container-fluid px-4">
                <h1 class="mt-4 mb-4">Trainer Dashboard</h1>

                <!-- Trainer Profile Section -->
                <div class="profile-header">
                    <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Trainer Image">
                    <div>
                        <h3><?= htmlspecialchars($trainer['name']); ?></h3>
                        <p><strong>Trainer ID:</strong> <?= htmlspecialchars($trainer['trainer_id']); ?></p>
                        <p><strong>Gym:</strong> <?= htmlspecialchars($gym['name']); ?> - <?= htmlspecialchars($gym['address']); ?></p>
                    </div>
                </div>

                <!-- Trainer Info Card -->
                <div class="info-card">
                    <h5 class="mb-3"><i class="fas fa-user-circle me-2 text-primary"></i>Trainer Information</h5>
                    <div class="info-row">
                        <div class="info-item"><strong>Email:</strong> <?= htmlspecialchars($trainer['email']); ?></div>
                        <div class="info-item"><strong>Phone:</strong> <?= htmlspecialchars($trainer['phone']); ?></div>
                        <div class="info-item"><strong>Specialization:</strong> <?= htmlspecialchars($trainer['specialization']); ?></div>
                        <div class="info-item"><strong>Rate per Session:</strong> Rs. <?= htmlspecialchars($trainer['rate_per_session']); ?></div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="info-card">
                    <h5 class="mb-3"><i class="fas fa-calendar-alt me-2 text-primary"></i>Account Details</h5>
                    <div class="info-row">
                        <div class="info-item"><strong>Joined On:</strong> <?= htmlspecialchars($trainer['created_at']); ?></div>
                        <div class="info-item"><strong>Last Updated:</strong> <?= htmlspecialchars($trainer['updated_at']); ?></div>
                    </div>
                    <div class="mt-4 text-center">
                        <h5>Status:</h5>
                        <?php $status_class = ($trainer['status'] === 'active') ? 'bg-success' : 'bg-danger'; ?>
                        <span class="badge <?= $status_class; ?> px-4 py-2 fs-6">
                            <?= ucfirst($trainer['status']); ?>
                        </span>
                    </div>
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
</body>

</html>