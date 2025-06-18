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
                            <a class="nav-link" href="../ads/edit.php">Edit</a>
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
                            <a class="nav-link" href="../AboutUs/edit.php">Edit</a>
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
                            <a class="nav-link" href="../Register/edit.php">Edit</a>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Logged in as:</div>
                Suman Poudel
            </div>
        </nav>
    </div>