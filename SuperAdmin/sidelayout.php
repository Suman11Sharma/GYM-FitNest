<?php
session_start();
?>
<?php require_once('Layouts/header.php'); ?>
<?php require_once('Layouts/navbar.php'); ?>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu bg-dark">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Home</div>
                    <a class="nav-link" href="../superAdminPage.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>
                    <div class="sb-sidenav-menu-heading">Management</div>

                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                        data-bs-target="#collapseAds" aria-expanded="false" aria-controls="collapseAds">
                        <div class="sb-nav-link-icon"><i class="fas fa-ad"></i></div>
                        Ads
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapseAds" aria-labelledby="headingAds" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="../ads/index.php">Index</a>
                            <a class="nav-link" href="../ads/create.php">Create</a>
                            <!-- <a class="nav-link" href="../ads/edit.php">Edit</a> -->
                        </nav>
                    </div>
                    <!-- Ads-Plan -->
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                        data-bs-target="#collapseAdsPlan" aria-expanded="false" aria-controls="collapseAdsPlan">
                        <div class="sb-nav-link-icon"><i class="fas fa-ad"></i></div>
                        Ads-Plan
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapseAdsPlan" aria-labelledby="headingAdsPlan" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="../Ads-Plan/create.php">Create</a>
                            <a class="nav-link" href="../Ads-Plan/index.php">Index</a>
                        </nav>
                    </div>

                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                        data-bs-target="#collapseAboutUs" aria-expanded="false" aria-controls="collapseAboutUs">
                        <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                        About Us
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapseAboutUs" aria-labelledby="headingAboutUs" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="../AboutUs/index.php">Index</a>
                            <a class="nav-link" href="../AboutUS/create.php">Create</a>
                            <!-- <a class="nav-link" href="../AboutUs/edit.php">Edit</a> -->
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
                            <a class="nav-link" href="../contactUs/index.php">Index</a>
                        </nav>
                    </div>

                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                        data-bs-target="#collapseRegister" aria-expanded="false" aria-controls="collapseRegister">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                        Register
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapseRegister" aria-labelledby="headingRegister" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="../Register/index.php">Index</a>
                            <a class="nav-link" href="../Register/create.php">Create</a>
                            <!-- <a class="nav-link" href="../Register/edit.php">Edit</a> -->
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
                            <a class="nav-link" href="../Create-Users/create.php">Create</a>
                            <a class="nav-link" href="../Create-Users/index.php">Index</a>
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
                            <a class="nav-link" href="../SaasPlans/create.php">Create</a>
                            <a class="nav-link" href="../SaasPlans/index.php">Index</a>
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
                            <a class="nav-link" href="../Renew-Gym/create.php">Create</a>
                            <a class="nav-link" href="../Renew-Gym/index.php">Index</a>
                        </nav>
                    </div>
                    <!-- Videos-->
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                        data-bs-target="#collapseVideos" aria-expanded="false" aria-controls="collapseVideos">
                        <div class="sb-nav-link-icon"><i class="fas fa-video"></i></div>
                        Videos
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapseVideos" aria-labelledby="headingVideos" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="../Videos/create.php">Create</a>
                            <a class="nav-link" href="../Videos/index.php">Index</a>
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