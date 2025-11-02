<?php require_once('Layouts/header.php'); ?>
<?php require_once('Layouts/navbar.php'); ?>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu bg-dark">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Home</div>
                    <a class="nav-link" href="../trainerPage.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>
                    <div class="sb-sidenav-menu-heading">Management</div>

                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                        data-bs-target="#collapseVideos" aria-expanded="false" aria-controls="collapseVideos">
                        <div class="sb-nav-link-icon"><i class="fas fa-video"></i></div>
                        Workout Videos
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseVideos" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="../Videos/index.php">Workout</a>
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